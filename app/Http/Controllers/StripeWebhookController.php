<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Enums\PayoutStatus;
use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Payout; // Added
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
        } catch (\UnexpectedValueException $e) { // Added for completeness
            Log::error('Stripe Webhook Invalid Payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
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
                break; // Added break

            case 'transfer.succeeded':
                $this->handleTransferSucceeded($event->data->object);
                break;
            case 'transfer.failed':
                $this->handleTransferFailed($event->data->object);
                break;
            case 'transfer.reversed':
                $this->handleTransferReversed($event->data->object);
                break;

            default:
                Log::info("Ignored Stripe event type: " . $event->type);
                break; // Added break
        }

        return response()->json(['status' => 'ok']); // Moved outside switch
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
            'status' => PaymentStatus::SUCCEEDED,
            'transaction_id' => $paymentIntent->id,
        ]);

        event(new OrderPaid($order));

        Log::info("Payment for order {$order->id} processed successfully.");
    }

    // New methods for transfer events
    protected function handleTransferSucceeded($transfer)
    {
        $payoutId = $transfer->metadata->payout_id ?? null;

        if ($payoutId) {
            $payout = Payout::find($payoutId);
            if ($payout) {
                $payout->status = PayoutStatus::PAID;
                $payout->save();
                Log::info("Payout {$payoutId} status updated to paid via webhook.");
            } else {
                Log::warning("Payout {$payoutId} not found for transfer.succeeded webhook.");
            }
        } else {
            Log::warning("payout_id not found in metadata for transfer.succeeded webhook.");
        }
    }

    protected function handleTransferFailed($transfer)
    {
        $payoutId = $transfer->metadata->payout_id ?? null;

        if ($payoutId) {
            $payout = Payout::find($payoutId);
            if ($payout) {
                $payout->status = PayoutStatus::FAILED;
                $payout->save();
                Log::error("Payout {$payoutId} status updated to failed via webhook. Failure code: {$transfer->failure_code}, message: {$transfer->failure_message}");
            } else {
                Log::warning("Payout {$payoutId} not found for transfer.failed webhook.");
            }
        } else {
            Log::warning("payout_id not found in metadata for transfer.failed webhook.");
        }
    }

    protected function handleTransferReversed($transfer)
    {
        $payoutId = $transfer->metadata->payout_id ?? null;

        if ($payoutId) {
            $payout = Payout::find($payoutId);
            if ($payout) {
                $payout->status = PayoutStatus::FAILED; // Mapping 'reversed' to 'failed'
                $payout->save();
                $reason = $transfer->reversals?->data[0]?->reason ?? 'N/A';
                Log::warning("Payout {$payoutId} status updated to failed (reversed) via webhook. Reversal reason: {$reason}");
            } else {
                Log::warning("Payout {$payoutId} not found for transfer.reversed webhook.");
            }
        } else {
            Log::warning("payout_id not found in metadata for transfer.reversed webhook.");
        }
    }
}
