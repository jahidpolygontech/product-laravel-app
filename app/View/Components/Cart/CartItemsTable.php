<?php

namespace App\View\Components\Cart;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use Illuminate\Support\Collection;

class CartItemsTable extends Component
{
    public function __construct(
        public Collection $cartItems
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.cart.cart-items-table');
    }
}


