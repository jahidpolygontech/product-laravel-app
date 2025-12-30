<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display order history
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('status', 'Please login to view your orders');
        }

        $orders = $user->orders()->with('items.product')->orderBy('created_at', 'desc')->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        $user = Auth::user();

        // Check if user is authorized to view this order
        if ($user && $order->user_id !== $user->id) {
            return redirect()->route('orders.index')->with('status', 'Unauthorized access');
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }
}

