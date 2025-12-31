<?php

namespace App\Services;

use App\DTOs\CartDTO;
use App\DTOs\CartItemDTO;
use App\DTOs\CheckoutDTO;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class CartService
{
    /**
     * Get cart for authenticated user or guest
     */
    public function getCart(?User $user = null): CartDTO
    {
        if ($user) {
            return $this->getAuthenticatedCart($user);
        }

        return $this->getSessionCart();
    }

    /**
     * Get authenticated user's cart from database
     */
    private function getAuthenticatedCart(User $user): CartDTO
    {
        $cartItems = $user->carts()->with('product')->get();

        $items = $cartItems->map(function ($cartItem) {
            return new CartItemDTO(
                id: $cartItem->id,
                product_id: $cartItem->product_id,
                product_name: $cartItem->product->name,
                price: $cartItem->price,
                quantity: $cartItem->quantity,
                subtotal: $cartItem->price * $cartItem->quantity,
                product: $cartItem->product,
            );
        });

        $total = $items->sum(fn($item) => $item->subtotal);

        return new CartDTO(
            items: $items,
            total: $total,
        );
    }

    /**
     * Get guest's cart from session
     */
    private function getSessionCart(): CartDTO
    {
        $sessionCart = session()->get('cart', []);
        $items = $this->formatSessionCart($sessionCart);

        $total = collect($items)->sum(fn($item) => $item->subtotal);

        return new CartDTO(
            items: collect($items),
            total: $total,
        );
    }

    /**
     * Format session cart data into CartItemDTO objects
     */
    private function formatSessionCart(array $sessionCart): array
    {
        $formatted = [];

        foreach ($sessionCart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $formatted[] = new CartItemDTO(
                    id: $productId,
                    product_id: $productId,
                    product_name: $product->name,
                    price: $item['price'],
                    quantity: $item['quantity'],
                    subtotal: $item['price'] * $item['quantity'],
                    product: $product,
                );
            }
        }

        return $formatted;
    }

    /**
     * Add product to cart
     */
    public function addToCart(Product $product, int $quantity, ?User $user = null): void
    {
        if ($user) {
            $this->addToAuthenticatedCart($user, $product, $quantity);
        }
        else {
            $this->addToSessionCart($product, $quantity);
        }
    }

    /**
     * Add product to authenticated user's cart
     */
    private function addToAuthenticatedCart(User $user, Product $product, int $quantity): void
    {
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }
    }

    /**
     * Add product to session cart
     */
    private function addToSessionCart(Product $product, int $quantity): void
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'quantity' => $quantity,
                'price' => $product->price,
            ];
        }

        session()->put('cart', $cart);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($cartId, ?User $user = null): void
    {
        if ($user) {
            $this->removeFromAuthenticatedCart($cartId, $user);
        } else {
            $this->removeFromSessionCart($cartId);
        }
    }

    /**
     * Remove item from authenticated user's cart
     */
    private function removeFromAuthenticatedCart(int $cartId, User $user): void
    {
        $cart = Cart::find($cartId);

        if ($cart && $cart->user_id === $user->id) {
            $cart->delete();
        }
    }

    /**
     * Remove item from session cart
     */
    private function removeFromSessionCart($productId): void
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItem(int $quantity, $cartId, ?User $user = null): void
    {
        if ($user) {
            $this->updateAuthenticatedCartItem($cartId, $quantity, $user);
        } else {
            $this->updateSessionCartItem($cartId, $quantity);
        }
    }

    /**
     * Update authenticated user's cart item
     */
    private function updateAuthenticatedCartItem(int $cartId, int $quantity, User $user): void
    {
        $cart = Cart::find($cartId);

        if ($cart && $cart->user_id === $user->id) {
            $product = $cart->product;
            $basePrice = $product->price;
            $newPrice = $this->calculatePriceByQuantity($basePrice, $quantity);

            $cart->update([
                'quantity' => $quantity,
                'price' => $newPrice,
            ]);
        }
    }

    /**
     * Update session cart item
     */
    private function updateSessionCartItem(int $productId, int $quantity): void
    {
        $sessionCart = session()->get('cart', []);

        if (isset($sessionCart[$productId])) {
            $product = Product::find($productId);
            if ($product) {
                $basePrice = $product->price;
                $newPrice = $this->calculatePriceByQuantity($basePrice, $quantity);

                $sessionCart[$productId]['quantity'] = $quantity;
                $sessionCart[$productId]['price'] = $newPrice;
                session()->put('cart', $sessionCart);
            }
        }
    }

    /**
     * Calculate product price based on quantity
     * Price increases by 2% for each additional unit
     */
    private function calculatePriceByQuantity(float $basePrice, int $quantity): float
    {
        $priceMultiplier = 1 + (($quantity - 1) * 0.02);
        return round($basePrice * $priceMultiplier, 2);
    }

    /**
     * Clear entire cart
     */
    public function clearCart(?User $user = null): void
    {
        if ($user) {
            Cart::where('user_id', $user->id)->delete();
        } else {
            session()->forget('cart');
        }
    }

    /**
     * Process checkout and create order
     */
    public function processCheckout(CheckoutDTO $checkout, ?User $user = null): Order
    {
        $cartData = $this->getCart($user);

        if ($cartData->isEmpty()) {
            throw new \Exception('Cart is empty');
        }

        if ($user) {
            return $this->processAuthenticatedCheckout($user, $checkout, $cartData);
        }

        return $this->processGuestCheckout($checkout, $cartData);
    }

    /**
     * Process checkout for authenticated user
     */
    private function processAuthenticatedCheckout(User $user, CheckoutDTO $checkout, CartDTO $cartData): Order
    {
        $order = Order::create([
            'user_id' => $user->id,
            'shipping_address' => $checkout->shipping_address,
            'phone' => $checkout->phone,
            'total_amount' => $cartData->total,
        ]);

        foreach ($cartData->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        $this->clearCart($user);

        return $order;
    }

    /**
     * Process checkout for guest
     */
    private function processGuestCheckout(CheckoutDTO $checkout, CartDTO $cartData): Order
    {
        $order = Order::create([
            'guest_name' => $checkout->name,
            'guest_email' => $checkout->email,
            'shipping_address' => $checkout->shipping_address,
            'phone' => $checkout->phone,
            'total_amount' => $cartData->total,
        ]);

        foreach ($cartData->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        $this->clearCart(null);

        return $order;
    }
}

