<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{public function index()
{
    $vendor = Auth::user();
    $store = $vendor->store;

    if (!$store) {
        return view('vendor.dashboard', [
            'missingStore' => true,
            'totalSales' => 0,
            'totalOrders' => 0,
            'totalProducts' => 0,
            'topProducts' => collect(),
        ]);
    }

    $totalSales = Order::whereHas('items.product', function ($query) use ($store) {
        $query->where('store_id', $store->id);
    })->where('status', 'paid')->sum('total');

    $totalOrders = Order::whereHas('items.product', function ($query) use ($store) {
        $query->where('store_id', $store->id);
    })->count();

    $totalProducts = Product::where('store_id', $store->id)->count();

    $topProducts = Product::where('store_id', $store->id)
        ->withCount('orders')->orderBy('orders_count', 'desc')->take(5)->get();

    return view('vendor.dashboard', compact('totalSales', 'totalOrders', 'totalProducts', 'topProducts') + ['missingStore' => false]);
}

}