<?php

namespace App\PaymentGateways;

use App\Contracts\PaymentGateway;
use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripePaymentGateway implements PaymentGateway
{
    public function process(Order $order, array $data): ?RedirectResponse
    {
        if (!config('services.stripe.secret')) {
            if (app()->environment('local', 'testing') && $data['payment_method_id'] === 'pm_card_visa') {
                // This is a mock payment for local testing
                Payment::create([
                    'order_id' => $order->id,
                    'payment_gateway' => 'stripe-mock',
                    'amount' => $order->total,
                    'currency' => 'usd',
                    'status' => 'succeeded',
                    'transaction_id' => 'mock_'.uniqid(),
                ]);

                event(new OrderPaid($order));
                return null;
            }

            throw new \Exception("Stripe is not configured.");
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $user = auth()->user();

        try {
            // Create a PaymentIntent and confirm it immediately.
            $paymentIntent = PaymentIntent::create([
                'amount' => $order->total * 100, // Amount in cents
                'currency' => 'usd',
                'customer' => $user->stripe_id,
                'payment_method' => $data['payment_method_id'],
                'metadata' => ['order_id' => $order->id],
                'off_session' => true, // charge the customer immediately
                'confirm' => true, // confirm the payment immediately
            ]);

            Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => 'stripe',
                'amount' => $order->total,
                'currency' => 'usd',
                'status' => 'succeeded',
                'transaction_id' => $paymentIntent->id,
            ]);

            // The payment was successful
            event(new OrderPaid($order));

        } catch (\Stripe\Exception\CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            throw $e;
        } catch (\Exception $e) {
            // Something else happened, like an invalid request
            throw $e;
        }

        return null; // No redirect needed for this flow
    }

    public function handleCallback(Request $request, Order $order): RedirectResponse
    {
        // This method is not used in the current Stripe flow, but is required by the interface.
        return redirect()->route('customer.orders.show', $order);
    }
}
