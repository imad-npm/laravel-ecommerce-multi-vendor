<?php

namespace App\PaymentGateways;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CardPaymentGateway implements PaymentGateway
{
    public function process(Order $order, array $data): ?RedirectResponse
    {
        // Redirect to a dedicated, simulated card payment view
        return redirect()->route('customer.payment.card.create', $order);
    }

    public function handleCallback(Request $request, Order $order)
    {
        // Simulated callback for card payments
        // In a real scenario, this would involve processing the card payment
        // and updating the payment status.
        $paymentId = $request->input('payment_id');
        $payment = Payment::find($paymentId);

        if ($payment) {
            $payment->status = 'succeeded';
            $payment->save();
            $order->status = 'paid';
            $order->save();
            event(new \App\Events\OrderPaid($order));
            return redirect()->route('customer.orders.show', $order)->with('success', 'Card payment successful!');
        }

        return redirect()->route('customer.orders.show', $order)->with('error', 'Card payment failed.');
    }

    private function validateCardDetails(array $data): bool
    {
        // Simulate card validation
        return true;
    }
}