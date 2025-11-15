<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\Order\OrderData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {}

    public function index(Request $request)
    {
        $orders = $this->orderService->getOrders($request);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Charger la relation items et produits
        $order->load('items.product', 'customer');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(OrderRequest $request, Order $order)
    {
        $orderData = OrderData::fromRequest($request);
        $this->orderService->updateOrder($order, $orderData);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function cancel(Order $order)
    {
        if ($this->orderService->cancelOrder($order)) {
            return redirect()->route('admin.orders.index')->with('success', 'Order cancelled successfully.');
        } else {
            return redirect()->route('admin.orders.index')->with('error', 'Order already cancelled.');
        }
    }
}