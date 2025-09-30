<?php

namespace App\PaymentGateways;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripePaymentGateway implements \App\Contracts\PaymentGateway
{
    public function process(Order $order, array $data): ?RedirectResponse
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_gateway' => 'stripe',
            'amount' => $order->total,
            'currency' => 'usd',
            'status' => 'initiated',
        ]);

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $order->total * 100, // Amount in cents
                'currency' => 'usd',
                'metadata' => ['order_id' => $order->id, 'payment_id' => $payment->id],
            ]);

            $payment->transaction_id = $paymentIntent->id;
            $payment->status = 'pending'; // Stripe PaymentIntent is initially pending confirmation
            $payment->save();

            // For client-side confirmation, we return a JSON response with client_secret
            // The frontend will then confirm the payment.
            // This is a deviation from the RedirectResponse, but necessary for Stripe Elements.
            // The PaymentController will need to handle this JSON response.
            return response()->json(['clientSecret' => $paymentIntent->client_secret, 'paymentId' => $payment->id]);
        } catch (\Exception $e) {
            $payment->status = 'failed';
            $payment->error_message = $e->getMessage();
            $payment->save();
            // In case of an error during PaymentIntent creation, we should redirect back with an error.
            return redirect()->route('customer.orders.show', $order)->with('error', 'Stripe payment initiation failed.');
        }
    }

    public function handleCallback(Request $request, Order $order): RedirectResponse
    {
        $payload = $request->all();
        // This method would be called by your Stripe webhook controller
        // to update the payment status based on Stripe events.
        // Example: retrieve PaymentIntent from payload, update Payment record.

        $paymentIntentId = $payload['data']['object']['id'];
        $status = $payload['data']['object']['status'];
        $paymentId = $payload['data']['object']['metadata']['payment_id'];

        $payment = Payment::find($paymentId);

        if ($payment) {
            if ($status === 'succeeded') {
                $payment->status = 'succeeded';
                $payment->order->status = 'paid';
                $payment->order->save();
                event(new \App\Events\OrderPaid($payment->order));
            } elseif ($status === 'failed') {
                $payment->status = 'failed';
                $payment->error_message = $payload['data']['object']['last_payment_error']['message'] ?? 'Payment failed';
            }
            $payment->save();
        }

        return redirect()->route('customer.orders.show', $order);
    }
}
