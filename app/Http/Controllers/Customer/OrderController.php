<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Services\OrderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $order->load('shippingAddress') ;
        return view('customer.orders.show', compact('order'));
    }

    public function create()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $shippingAddresses = $user->shippingAddresses;

        return view('customer.checkout.create', compact('cart', 'shippingAddresses'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'shipping_address_id' => 'required|exists:shipping_addresses,id,user_id,' . $user->id,
        ]);

        $order = $this->orderService->createPendingOrder($user, $validated['shipping_address_id']);
        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'Could not create order. Your cart might be empty.');
        }

        return redirect()->route('customer.orders.payments.create', $order);
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
