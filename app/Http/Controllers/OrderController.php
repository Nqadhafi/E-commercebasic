<?php

namespace App\Http\Controllers;

use App\Models\Order;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->paginate(12);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id() || auth()->user()->isAdmin(), 403);
        $order->load('items');
        return view('orders.show', compact('order'));
    }
}
