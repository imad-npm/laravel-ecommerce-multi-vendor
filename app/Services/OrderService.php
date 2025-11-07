<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\VendorEarningService;

class OrderService
{
    protected $vendorEarningService;

    public function __construct(VendorEarningService $vendorEarningService)
    {
        $this->vendorEarningService = $vendorEarningService;
    }

    public function createPendingOrder(User $user, int $shippingAddressId): ?Order
    {
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return null;
        }

        $total = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);

        $order = Order::create([
            'user_id' => $user->id,
            'shipping_address_id' => $shippingAddressId,
            'total' => $total,
            'status' => 'pending',
        ]);

        $this->createOrderItems($order, $cart);

        return $order;
    }

    private function createOrderItems(Order $order, $cart): void
    {
        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }
    }
    
    public function markAsPaid(Order $order): void
    {
        $order->update(['status' => 'paid']);
        $this->vendorEarningService->createVendorEarnings($order);
        $order->user->cart->items()->delete();
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
