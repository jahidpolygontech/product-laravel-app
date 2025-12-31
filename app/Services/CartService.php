<?php

namespace App\Services;

use App\DTOs\CartDTO;
use App\DTOs\CartItemDTO;
use App\DTOs\CheckoutDTO;
use App\Exceptions\CartException;
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
        return $user
            ? $this->getAuthenticatedCart($user)
            : $this->getSessionCart();
    }

    /**
     * -------------------------
     * CART FETCHING
     * -------------------------
     */

    private function getAuthenticatedCart(User $user): CartDTO
    {
        $cartItems = $user->carts()->with('product')->get();

        $items = $cartItems->map(fn ($cartItem) =>
        new CartItemDTO(
            id: $cartItem->id,
            product_id: $cartItem->product_id,
            product_name: $cartItem->product->name,
            price: $cartItem->price,
            quantity: $cartItem->quantity,
            subtotal: $cartItem->price * $cartItem->quantity,
            product: $cartItem->product,
        )
        );

        return new CartDTO(
            items: $items,
            total: $items->sum(fn ($item) => $item->subtotal),
        );
    }

    private function getSessionCart(): CartDTO
    {
        $sessionCart = session()->get('cart', []);

        $items = collect($sessionCart)
            ->map(fn ($item, $productId) => $this->buildSessionCartItem($productId, $item))
            ->filter()
            ->values();

        return new CartDTO(
            items: $items,
            total: $items->sum(fn ($item) => $item->subtotal),
        );
    }

    private function buildSessionCartItem(int $productId, array $item): ?CartItemDTO
    {
        $product = Product::find($productId);

        if (! $product) {
            return null;
        }

        return new CartItemDTO(
            id: $productId,
            product_id: $productId,
            product_name: $product->name,
            price: $item['price'],
            quantity: $item['quantity'],
            subtotal: $item['price'] * $item['quantity'],
            product: $product,
        );
    }

    /**
     * -------------------------
     * ADD TO CART
     * -------------------------
     */

    public function addToCart(Product $product, int $quantity, ?User $user = null): void
    {
        $user
            ? $this->addToAuthenticatedCart($user, $product, $quantity)
            : $this->addToSessionCart($product, $quantity);
    }

    private function addToAuthenticatedCart(User $user, Product $product, int $quantity): void
    {
        $cartItem = Cart::firstOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $product->id,
            ],
            [
                'quantity' => 0,
                'price' => $product->price,
            ]
        );

        $cartItem->increment('quantity', $quantity);
    }

    private function addToSessionCart(Product $product, int $quantity): void
    {
        $cart = session()->get('cart', []);

        $cart[$product->id]['quantity'] =
            ($cart[$product->id]['quantity'] ?? 0) + $quantity;

        $cart[$product->id]['price'] = $product->price;

        session()->put('cart', $cart);
    }

    /**
     * -------------------------
     * REMOVE FROM CART
     * -------------------------
     */

    public function removeFromCart(int $cartId, ?User $user = null): void
    {
        $user
            ? $this->removeFromAuthenticatedCart($cartId, $user)
            : $this->removeFromSessionCart($cartId);
    }

    private function removeFromAuthenticatedCart(int $cartId, User $user): void
    {
        $cart = Cart::find($cartId);

        if (! $cart) {
            throw new CartException('Cart item not found');
        }

        if ($cart->user_id !== $user->id) {
            throw new CartException('Unauthorized cart access');
        }

        $cart->delete();
    }

    private function removeFromSessionCart(int $productId): void
    {
        $cart = session()->get('cart', []);

        if (! isset($cart[$productId])) {
            throw new CartException('Cart item not found');
        }

        unset($cart[$productId]);
        session()->put('cart', $cart);
    }

    /**
     * -------------------------
     * UPDATE CART
     * -------------------------
     */

    public function updateCartItem(int $quantity, int $cartId, ?User $user = null): void
    {
        $user
            ? $this->updateAuthenticatedCartItem($cartId, $quantity, $user)
            : $this->updateSessionCartItem($cartId, $quantity);
    }

    private function updateAuthenticatedCartItem(int $cartId, int $quantity, User $user): void
    {
        $cart = Cart::find($cartId);

        if (! $cart) {
            throw new CartException('Cart item not found');
        }

        if ($cart->user_id !== $user->id) {
            throw new CartException('Unauthorized cart access');
        }

        $cart->update([
            'quantity' => $quantity,
            'price' => $this->calculatePriceByQuantity(
                $cart->product->price,
                $quantity
            ),
        ]);
    }

    private function updateSessionCartItem(int $productId, int $quantity): void
    {
        $cart = session()->get('cart', []);

        if (! isset($cart[$productId])) {
            throw new CartException('Cart item not found');
        }

        $product = Product::find($productId);

        if (! $product) {
            throw new CartException('Product not found');
        }

        $cart[$productId]['quantity'] = $quantity;
        $cart[$productId]['price'] =
            $this->calculatePriceByQuantity($product->price, $quantity);

        session()->put('cart', $cart);
    }

    /**
     * -------------------------
     * CLEAR CART
     * -------------------------
     */

    public function clearCart(?User $user = null): void
    {
        $user
            ? Cart::where('user_id', $user->id)->delete()
            : session()->forget('cart');
    }

    /**
     * -------------------------
     * CHECKOUT
     * -------------------------
     */

    public function processCheckout(CheckoutDTO $checkout, ?User $user = null): Order
    {
        $cart = $this->getCart($user);

        if ($cart->isEmpty()) {
            throw new CartException('Cart is empty');
        }

        return $user
            ? $this->processAuthenticatedCheckout($user, $checkout, $cart)
            : $this->processGuestCheckout($checkout, $cart);
    }

    private function processAuthenticatedCheckout(
        User $user,
        CheckoutDTO $checkout,
        CartDTO $cart
    ): Order {
        $order = Order::create([
            'user_id' => $user->id,
            'shipping_address' => $checkout->shipping_address,
            'phone' => $checkout->phone,
            'total_amount' => $cart->total,
        ]);

        $this->createOrderItems($order, $cart->items);
        $this->clearCart($user);

        return $order;
    }

    private function processGuestCheckout(CheckoutDTO $checkout, CartDTO $cart): Order {
        $order = Order::create([
            'guest_name' => $checkout->name,
            'guest_email' => $checkout->email,
            'shipping_address' => $checkout->shipping_address,
            'phone' => $checkout->phone,
            'total_amount' => $cart->total,
        ]);

        $this->createOrderItems($order, $cart->items);
        $this->clearCart();
        return $order;
    }

    private function createOrderItems(Order $order, Collection $items): void
    {
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }
    }

    /**
     * -------------------------
     * PRICING
     * -------------------------
     */

    private function calculatePriceByQuantity(float $basePrice, int $quantity): float
    {
        $multiplier = 1 + (($quantity - 1) * 0.02);
        return round($basePrice * $multiplier, 2);
    }
}
