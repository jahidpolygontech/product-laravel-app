<?php

namespace App\View\Components\Cart;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OrderSummary extends Component
{
    public function __construct(
        public float $total
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.cart.order-summary');
    }
}

