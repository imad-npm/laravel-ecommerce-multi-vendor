<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
public function handleWebhook(Request $request)
{
    $payload = $request->getContent();
    $sigHeader = $request->header('Stripe-Signature');
    $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

    try {
        $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
    } catch (SignatureVerificationException $e) {
        Log::error("Stripe webhook signature verification failed.");
        return response()->json(['error' => 'Invalid signature'], 400);
    }

    switch ($event->type) {
        case 'payment_intent.succeeded':
            $paymentIntent = $event->data->object;
            $this->handlePaymentIntentSucceeded($paymentIntent);
            return response()->json(['status' => 'ok']);
        default:
            Log::info("Received unknown Stripe event type: " . $event->type);
            return response()->json(['status' => 'ignored']);
    }
}

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id;
        $order = Order::find($orderId);

        if ($order) {
            // Check if payment already exists
            if (Payment::where('transaction_id', $paymentIntent->id)->exists()) {
                Log::info("Payment already processed for transaction ID: " . $paymentIntent->id);
                return;
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => 'stripe',
                'amount' => $order->total,
                'currency' => 'usd',
                'status' => 'succeeded',
                'transaction_id' => $paymentIntent->id,
            ]);

            event(new OrderPaid($order));

            Log::info("Payment for order {$order->id} processed successfully.");
        } else {
            Log::error("Order not found for payment intent: " . $paymentIntent->id);
        }
    }
}