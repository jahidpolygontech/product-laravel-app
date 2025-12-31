<?php
namespace App\Http\Controllers;

use App\DTOs\CheckoutDTO;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    /**
     * Display cart
     */
    public function index()
    {
        $cart = $this->cartService->getCart(Auth::user());

        return view('cart.index', [
            'cartItems' => $cart->items,
            'total'     => $cart->total,
        ]);
    }

    /**
     * Add product to cart
     */
    public function add(AddToCartRequest $request, Product $product)
    {
        $this->cartService->addToCart(
            product: $product,
            quantity: $request->input('quantity', 1),
            user: Auth::user()
        );

        return redirect()
            ->route('cart.index')
            ->with('status', 'Product added to cart successfully!');
    }

    /**
     * Remove item from cart
     */
    public function remove(int $cartId)
    {
        $this->cartService->removeFromCart(
            cartId: $cartId,
            user: Auth::user()
        );

        return redirect()
            ->route('cart.index')
            ->with('status', 'Item removed from cart');
    }

    /**
     * Update cart item quantity
     */
    public function update(UpdateCartRequest $request, int $cartId)
    {
        $this->cartService->updateCartItem(
            quantity: $request->quantity,
            cartId: $cartId,
            user: Auth::user()
        );

        return redirect()
            ->route('cart.index')
            ->with('status', 'Cart updated successfully!');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $this->cartService->clearCart(Auth::user());

        return redirect()
            ->route('cart.index')
            ->with('status', 'Cart cleared successfully!');
    }

    /**
     * Show checkout page
     */
    public function checkout()
    {
        $cart = $this->cartService->getCart(Auth::user());

        return view('checkout', [
            'cartItems' => $cart->items,
            'total'     => $cart->total,
            'user'      => Auth::user(),
        ]);
    }

    /**
     * Process checkout
     */
    public function processCheckout(CheckoutRequest $request)
    {
        $checkoutDTO = CheckoutDTO::fromRequest(
            $request->validated()
        );

        $this->cartService->processCheckout(
            checkout: $checkoutDTO,
            user: Auth::user()
        );

        return redirect()
            ->route('checkout.success')
            ->with('status', 'Order placed successfully!');
    }
}
