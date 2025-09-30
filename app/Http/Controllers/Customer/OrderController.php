<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;
    protected $orderService;
    protected $paymentService;

    public function __construct(OrderService $orderService, PaymentService $paymentService)
    {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    public function create()
    {
        $cart = Auth::user()->cart;

       /* if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
*/
        return view('customer.orders.create', compact('cart'));
    }

    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $order = $this->orderService->createOrderFromCart($user, $validated);

        if (!$order) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        try {
            $result = $this->paymentService->process($order, $validated);

            if (isset($result['redirect'])) {
                return redirect($result['redirect']);
            }

            return redirect()->route('customer.orders.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }

    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);

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

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('customer.orders.show', compact('order'));
    }

  
}
