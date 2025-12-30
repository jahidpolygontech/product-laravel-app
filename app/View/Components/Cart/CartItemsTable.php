<?php

namespace App\View\Components\Cart;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CartItemsTable extends Component
{
    public function __construct(
        public array $cartItems
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.cart.cart-items-table');
    }
}

