<?php

namespace App\Services;

use App\DataTransferObjects\OrderData;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentGateway;

use App\Services\VendorEarningService;

class OrderService
{
    protected $vendorEarningService;

    public function __construct(VendorEarningService $vendorEarningService)
    {
        $this->vendorEarningService = $vendorEarningService;
    }
    public function createOrderFromCart(User $user, array $validatedData, int $shippingAddressId)
    {
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return null; // Or throw an exception
        }

        $total = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);

        $order = Order::create([
            'user_id' => $user->id,
            'shipping_address_id' => $shippingAddressId,
            'payment_method' => $validatedData['payment_method'],
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        $this->vendorEarningService->createVendorEarnings($order);

        $cart->items()->delete();

        return $order;
    }

    public function cancelOrder(Order $order): bool
    {
        if ($order->status !== 'pending') {
            return false;
        }

        return $order->update(['status' => 'cancelled']);
    }

    public function getUserOrders(User $user)
    {
        return $user->orders()->with('items.product')->latest()->get();
    }

    public function processPayment(Order $order, array $validatedData, PaymentGateway $paymentGateway): ?\Illuminate\Http\RedirectResponse
    {
        if ($order->status !== 'pending' || $order->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized or already processed.');
        }

        $order->payment_method = $validatedData['payment_method'];
        $order->save();

        return $paymentGateway->process($order, $validatedData);
    }

    public function getOrders(Request $request)
    {
        $query = Order::with('customer')->latest();

        if ($request->has('search')) {
            $query->where('id', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        return $query->paginate(10);
    }
}
