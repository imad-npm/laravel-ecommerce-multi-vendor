<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\PaymentIntent;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (SignatureVerificationException $e) {
            Log::error("Stripe webhook signature verification failed: " . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {

            case 'checkout.session.completed':
                $session = $event->data->object;

                try {
                    // Retrieve the PaymentIntent to access metadata
                    Stripe::setApiKey(config('services.stripe.secret'));
                    $paymentIntent = PaymentIntent::retrieve($session->payment_intent);

                    $this->handlePaymentIntentSucceeded($paymentIntent);
                } catch (\Exception $e) {
                    Log::error("Failed to retrieve PaymentIntent: " . $e->getMessage());
                }

                return response()->json(['status' => 'ok']);

            default:
                Log::info("Ignored Stripe event type: " . $event->type);
                return response()->json(['status' => 'ignored']);
        }
    }

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;
        $order = $orderId ? Order::find($orderId) : null;

        if (!$order) {
            Log::error("Order not found for payment intent: " . $paymentIntent->id);
            return;
        }

        // Avoid duplicate payments
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
    }
}
