<?php

namespace App\Http\Controllers;

use App\DTOs\CheckoutDTO;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    /**
     * Display the cart
     */
    public function index()
    {
        $user = Auth::user();
        $cartData = $this->cartService->getCart($user);

        return view('cart.index', [
            'cartItems' => $cartData->items,
            'total' => $cartData->total,
        ]);
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product)
    {
        $user = Auth::user();
        $quantity = $request->input('quantity', 1);
        $buyNow = $request->input('buy_now', false);

        $this->cartService->addToCart($product, $quantity, $user);

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

        try {
            $this->cartService->removeFromCart($cartId, $user);
        }
        catch (\Exception $e) {
            return redirect()->route('cart.index')->with('status', 'Unauthorized action');
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

        try {
            $this->cartService->updateCartItem($validated['quantity'], $cartId, $user);
        }
        catch (\Exception $e) {
            return redirect()->route('cart.index')->with('status', 'Unauthorized action');
        }

        return redirect()->route('cart.index')->with('status', 'Cart updated successfully!');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $user = Auth::user();
        $this->cartService->clearCart($user);

        return redirect()->route('cart.index')->with('status', 'Cart cleared successfully!');
    }

    /**
     * Show checkout page
     */
    public function checkout()
    {
        $user = Auth::user();
        $cartData = $this->cartService->getCart($user);

        if ($cartData->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Your cart is empty');
        }

        return view('checkout', [
            'cartItems' => $cartData->items,
            'total' => $cartData->total,
            'user' => $user,
        ]);
    }

    /**
     * Process checkout and create order
     */
    public function processCheckout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $validated = $request->validate([
                'shipping_address' => 'required|string',
                'phone' => 'required|string',
            ]);
            $checkout = CheckoutDTO::fromArray($validated);
        } else {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'shipping_address' => 'required|string',
                'phone' => 'required|string',
            ]);
            $checkout = CheckoutDTO::fromArray($validated);
        }

        try {
            $order = $this->cartService->processCheckout($checkout, $user);
            return redirect()->route('checkout.success')->with('status', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('status', 'Error processing checkout: ' . $e->getMessage());
        }
    }
}
