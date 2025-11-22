<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Services\OrderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Order\StoreOrderRequest;

class OrderController extends Controller
{
    use AuthorizesRequests;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = $this->orderService->getUserOrders(Auth::user());
        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('customer.orders.show', compact('order'));
    }

    public function create()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart-items.index')->with('error', 'Your cart is empty.');
        }

        $shippingAddresses = $user->shippingAddresses;

        return view('customer.orders.create', compact('cart', 'shippingAddresses'));
    }

    public function store(StoreOrderRequest $request)
    {
        $user = Auth::user();

        $order = $this->orderService->createPendingOrder($user, $request->validated('shipping_address_id'));
        if (!$order) {
            return redirect()->route('cart-items.index')->with('error', 'Could not create order. Your cart might be empty.');
        }
        $user->cart->items()->delete() ;

        return redirect()->route('customer.orders.checkout', $order);
    }

    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);

        if (!$this->orderService->cancelOrder($order)) {
            return redirect()->route('customer.orders.index')->with('error', 'Only pending orders can be cancelled.');
        }

        return redirect()->route('customer.orders.index')->with('success', 'Order has been cancelled.');
    }
}
