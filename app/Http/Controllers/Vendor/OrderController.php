<?php
// app/Http/Controllers/Vendor/OrderController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\VendorOrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(protected VendorOrderService $vendorOrderService)
    {}

    public function index()
    {
        if (!Auth::user()->store) {
            return redirect()->route('vendor.store.create')->with('error', 'You need to create a store first to view orders.');
        }

        $items = $this->vendorOrderService->getOrderItemsForAuthenticatedStore(Auth::user());

        return view('vendor.orders.index', compact('items'));
    }
}
