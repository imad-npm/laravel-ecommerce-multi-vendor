<?php

namespace App\Services;

use App\DataTransferObjects\OrderData;
use App\Models\Order;
use App\Models\OrderItem; // Added
use App\Models\ShippingAddress;
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

        $shippingAddress = ShippingAddress::findOrFail($shippingAddressId);

        $total = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);
        $order = Order::create([
            'user_id' => $user->id,
            'shipping_address_line_1' => $shippingAddress->address_line_1,
            'shipping_city' => $shippingAddress->city,
            'shipping_postal_code' => $shippingAddress->postal_code,
            'shipping_country' => $shippingAddress->shipping_country,
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

    public function getVendorOrderItems(User $vendor) // Added
    {
        $store = $vendor->store;

        if (!$store) {
            return collect(); // Return an empty collection if no store is found
        }

        return OrderItem::with(['order', 'product', 'order.customer'])
            ->whereHas('product', function ($query) use ($store) {
                $query->where('store_id', $store->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function updateOrder(Order $order, OrderData $orderData): Order
    {
        $order->update([
            'status' => $orderData->status,
        ]);

        return $order;
    }
}
