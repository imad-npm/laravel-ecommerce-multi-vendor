<?php

namespace App\Services;

use App\DataTransferObjects\OrderData;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrderFromCart(User $user, array $validatedData)
    {
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return null; // Or throw an exception
        }

        $total = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);

        $order = Order::create([
            'user_id' => $user->id,
            'address' => $validatedData['address'],
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

        $cart->items()->delete();

        return $order;
    }

    public function simulatePayment(Order $order, array $validatedData): \Illuminate\Http\RedirectResponse|null
    {
        switch ($validatedData['payment_method']) {
            case 'card':
                if (!$this->validateCardDetails($validatedData)) {
                    return back()->with('error', 'Invalid card details.');
                }
                $order->status = 'paid';
                break;

            case 'paypal':
                return redirect()->route('payment.paypal')->with('order_id', $order->id);

            case 'stripe':
                return redirect()->route('payment.stripe')->with('order_id', $order->id);

            case 'cod':
                $order->status = 'pending';
                break;
        }

        $order->payment_method = $validatedData['payment_method'];
        $order->save();

        return null;
    }

    private function validateCardDetails(array $data): bool
    {
        // Simulate card validation
        return true;
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

    public function processRetryPayment(Order $order, array $validatedData): \Illuminate\Http\RedirectResponse|null
    {
        if ($order->status !== 'pending' || $order->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized or already processed.');
        }

        return $this->simulatePayment($order, $validatedData);
    }
}