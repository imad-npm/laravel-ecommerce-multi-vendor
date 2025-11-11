<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function create(Request $request, Order $order)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $line_items = [];
        foreach ($order->items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                    'unit_amount' => $item->price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        try {
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => route('customer.orders.show', $order),
                'cancel_url' => route('customer.orders.show', $order),
                'payment_intent_data' => [
                    'metadata' => [
                        'order_id' => $order->id,
                    ],
                ],

            ]);

            return redirect($checkout_session->url);
        } catch (\Exception $e) {
            Log::error('Stripe Checkout session creation failed: ' . $e->getMessage());
            return redirect()->route('customer.orders.show', $order)->with('error', 'Could not connect to Stripe.');
        }
    }
}
