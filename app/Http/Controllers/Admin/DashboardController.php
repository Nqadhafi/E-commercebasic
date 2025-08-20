<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'orders_today' => Order::whereDate('created_at', now()->toDateString())->count(),
            'orders_pending' => Order::where('status','pending')->count(),
            'revenue_month' => Order::whereMonth('created_at', now()->month)->sum('grandtotal'),
            'low_stock' => Product::where('stock','<=',5)->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}
