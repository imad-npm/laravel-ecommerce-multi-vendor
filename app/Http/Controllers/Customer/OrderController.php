<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function create()
    {
        $cart = Auth::user()->cart;

        if ($cart->items->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty.');
        }

        return view('customer.orders.create', compact('cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,card,paypal,stripe',
            'card_number' => 'nullable|required_if:payment_method,card|numeric|digits:16',
            'expiry_date' => 'nullable|required_if:payment_method,card|date_format:m/y',
            'cvv' => 'nullable|required_if:payment_method,card|numeric|digits:3',
        ]);

        $user = Auth::user();
        $order = $this->orderService->createOrderFromCart($user, $validated);

        if (!$order) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $redirect = $this->orderService->simulatePayment($order, $validated);
        if ($redirect) return $redirect;

        return redirect()->route('customer.orders.index')->with('success', 'Order placed successfully!');
    }

    public function retryPayment(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('customer.orders.index')->with('error', 'This order is already processed.');
        }

        return view('customer.orders.retry-payment', compact('order'));
    }

    public function processRetryPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cod,card,paypal,stripe',
            'card_number' => 'nullable|required_if:payment_method,card|numeric|digits:16',
            'expiry_date' => 'nullable|required_if:payment_method,card|date_format:m/y',
            'cvv' => 'nullable|required_if:payment_method,card|numeric|digits:3',
        ]);

        $redirect = $this->orderService->processRetryPayment($order, $validated);
        if ($redirect) return $redirect;

        return redirect()->route('customer.orders.index')->with('success', 'Payment retried successfully!');
    }

    public function cancel(Order $order)
    {
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized');
        }

        if (!$this->orderService->cancelOrder($order)) {
            return redirect()->route('customer.orders.index')->with('error', 'Only pending orders can be cancelled.');
        }

        return redirect()->route('customer.orders.index')->with('success', 'Order has been cancelled.');
    }

    public function index()
    {
        $orders = $this->orderService->getUserOrders(Auth::user());
        return view('customer.orders.index', compact('orders'));
    }
}