<?php

namespace App\View\Components\Cart;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CartItemRow extends Component
{
    public function __construct(
        public array $item
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.cart.cart-item-row');
    }
}

