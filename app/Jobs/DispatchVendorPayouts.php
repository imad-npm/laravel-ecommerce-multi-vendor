<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\VendorEarning;
use App\Models\Payout;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Transfer;
use Throwable;

class DispatchVendorPayouts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Stripe::setApiKey(config('services.stripe.secret')); // Set Stripe API key

        $threshold = config('commission.payout_threshold');

        $vendors = User::whereHas('vendorEarnings', function ($query) use ($threshold) {
            $query->where('is_paid', false)
                  ->select(DB::raw('sum(net_earnings) as total_unpaid_earnings'))
                  ->groupBy('vendor_id')
                  ->having('total_unpaid_earnings', '>=', $threshold);
        })->get();

        foreach ($vendors as $vendor) {
            DB::transaction(function () use ($vendor, $threshold) {
                $unpaidEarnings = $vendor->vendorEarnings()->where('is_paid', false)->get();
                $totalUnpaid = $unpaidEarnings->sum('net_earnings');

                // Ensure vendor has a Stripe account ID
                if (empty($vendor->stripe_account_id)) {
                    \Log::warning("Vendor {$vendor->id} does not have a Stripe account ID for payout.");
                    return;
                }

                if ($totalUnpaid >= $threshold) {
                    $payout = Payout::create([
                        'vendor_id' => $vendor->id,
                        'amount' => $totalUnpaid,
                        'status' => 'pending', // Initial status
                        'payment_method' => 'stripe',
                        'payment_details' => json_encode(['note' => 'Auto-generated payout via Stripe']),
                    ]);

                    try {
                        $transfer = Transfer::create([
                            'amount' => (int) ($totalUnpaid * 100), // Stripe amounts are in cents
                            'currency' => config('commission.currency', 'usd'), // Use configurable currency
                            'destination' => $vendor->stripe_account_id,
                            'metadata' => [
                                'payout_id' => $payout->id,
                                'vendor_id' => $vendor->id,
                            ],
                        ]);

                        $payout->transaction_id = $transfer->id;
                        $payout->status = $transfer->status; // 'pending', 'paid', 'failed' etc.
                        $payout->save();

                        // Mark these earnings as paid only if transfer is successful/pending
                        if (in_array($transfer->status, ['pending', 'paid', 'succeeded'])) {
                            $vendor->vendorEarnings()->whereIn('id', $unpaidEarnings->pluck('id'))->update(['is_paid' => true, 'payout_id' => $payout->id]);
                        } else {
                            \Log::error("Stripe transfer failed for payout {$payout->id}: " . $transfer->status);
                            $payout->status = 'failed';
                            $payout->save();
                        }

                    } catch (Throwable $e) {
                        \Log::error("Stripe API error for payout {$payout->id}: " . $e->getMessage());
                        $payout->status = 'failed';
                        $payout->payment_details = json_encode(['error' => $e->getMessage()]);
                        $payout->save();
                    }
                }
            });
        }
    }
}
