<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = collect();
        $total = 0;

        if ($user) {
            // For authenticated users, get from database
            $cartItems = $user->carts()->with('product')->get();
            $total = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        } else {
            // For guests, get from session
            $sessionCart = session()->get('cart', []);
            $cartItems = $this->formatSessionCart($sessionCart);
            $total = collect($cartItems)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Format session cart data
     */
    private function formatSessionCart($sessionCart)
    {
        $formatted = [];
        foreach ($sessionCart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $formatted[] = [
                    'id' => $productId,
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ];
            }
        }
        return $formatted;
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product)
    {
        $user = Auth::user();
        $quantity = $request->input('quantity', 1);
        $buyNow = $request->input('buy_now', false);

        if ($user) {
            // For authenticated users, save to database
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
        } else {
            // For guests, save to session
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

        // If Buy Now button was clicked, redirect to cart
        if ($buyNow) {
            return redirect()->route('cart.index')->with('status', 'Product added to cart! Proceed to checkout.');
        }

        return redirect()->back()->with('status', 'Product added to cart successfully!');
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $cartId = null)
    {
        $user = Auth::user();

        if ($user) {
            // For authenticated users
            $cart = Cart::find($cartId);
            if (!$cart || $cart->user_id !== $user->id) {
                return redirect()->route('cart.index')->with('status', 'Unauthorized action');
            }
            $cart->delete();
        } else {
            // For guests, remove from session
            $cart = session()->get('cart', []);
            if (isset($cart[$cartId])) {
                unset($cart[$cartId]);
                session()->put('cart', $cart);
            }
        }

        return redirect()->route('cart.index')->with('status', 'Item removed from cart');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $cartId = null)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($user) {
            // For authenticated users
            $cart = Cart::find($cartId);
            if (!$cart || $cart->user_id !== $user->id) {
                return redirect()->route('cart.index')->with('status', 'Unauthorized action');
            }
            $cart->update(['quantity' => $validated['quantity']]);
        } else {
            // For guests, update session
            $sessionCart = session()->get('cart', []);
            if (isset($sessionCart[$cartId])) {
                $sessionCart[$cartId]['quantity'] = $validated['quantity'];
                session()->put('cart', $sessionCart);
            }
        }

        return redirect()->route('cart.index')->with('status', 'Cart updated successfully!');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $user = Auth::user();

        if ($user) {
            Cart::where('user_id', $user->id)->delete();
        } else {
            session()->forget('cart');
        }

        return redirect()->route('cart.index')->with('status', 'Cart cleared successfully!');
    }

    /**
     * Show checkout page
     */
    public function checkout()
    {
        $user = Auth::user();
        $cartItems = collect();
        $total = 0;

        if ($user) {
            // For authenticated users, get from database
            $cartItems = $user->carts()->with('product')->get();
            $total = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        } else {
            // For guests, get from session
            $sessionCart = session()->get('cart', []);
            if (empty($sessionCart)) {
                return redirect()->route('cart.index')->with('status', 'Your cart is empty');
            }
            $cartItems = $this->formatSessionCart($sessionCart);
            $total = collect($cartItems)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });
        }

        if (empty($cartItems) || (is_countable($cartItems) && count($cartItems) == 0)) {
            return redirect()->route('cart.index')->with('status', 'Your cart is empty');
        }

        return view('checkout', compact('cartItems', 'total', 'user'));
    }

    /**
     * Process checkout
     */
    public function processCheckout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // For authenticated users
            $validated = $request->validate([
                'shipping_address' => 'required|string',
                'phone' => 'required|string',
            ]);

            // Save order to database and clear cart
            Cart::where('user_id', $user->id)->delete();

            return redirect()->route('checkout.success')->with('status', 'Order placed successfully!');
        } else {
            // For guests
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'shipping_address' => 'required|string',
                'phone' => 'required|string',
            ]);

            // Clear session cart
            session()->forget('cart');

            return redirect()->route('checkout.success')->with('status', 'Order placed successfully!');
        }
    }
}
