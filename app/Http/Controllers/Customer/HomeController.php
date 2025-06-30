<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $categories = Category::all();
        // Featured: top 4 by sold_count (number of orders)
        $featured = \App\Models\Product::withCount(['orders as sold_count'])
            ->orderByDesc('sold_count')
            ->take(4)
            ->get();
        // Recommended: 4 random products not purchased by user
        $recommended = \App\Models\Product::whereDoesntHave('orders', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->inRandomOrder()->take(4)->get();
        // Recent orders: last 2 orders for user
        $orders = $user->orders()->latest()->take(2)->with('items.product')->get();
        return view('customer.home', compact('user', 'featured', 'recommended', 'orders', 'categories'));
    }
}
