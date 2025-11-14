<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('status', OrderStatus::PAID)->sum('total');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', UserRole::CUSTOMER)->count();
        $topProducts = Product::withCount('orders')->orderBy('orders_count', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalSales', 'totalOrders', 'totalCustomers', 'topProducts'));
    }
}
