<?php

namespace App\Jobs;

use App\Enums\PayoutStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\VendorEarning;
use App\Models\Payout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Transfer;
use Throwable;

class DispatchVendorPayouts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $threshold = config('commission.payout_threshold', 50);

        // Get vendors with unpaid earnings >= threshold
        $vendors = User::whereHas('vendorEarnings', fn($q) => $q->where('is_paid', false))->get()
            ->filter(fn($v) => $v->vendorEarnings()->where('is_paid', false)->sum('net_earnings') >= $threshold);

        foreach ($vendors as $vendor) {
            DB::transaction(function () use ($vendor) {
                $unpaidEarnings = $vendor->vendorEarnings()->where('is_paid', false)->get();
                $totalUnpaid = $unpaidEarnings->sum('net_earnings');

                if (empty($vendor->stripe_account_id)) {
                    Log::warning("Vendor {$vendor->id} has no Stripe account");
                    return;
                }

                if ($totalUnpaid <= 0) return;

                $payout = Payout::create([
                    'vendor_id' => $vendor->id,
                    'amount' => $totalUnpaid,
                    'status' => PayoutStatus::PENDING,
                    'payment_method' => 'stripe',
                ]);

                try {
                    $transfer = Transfer::create([
                        'amount' => (int) ($totalUnpaid * 100),
                        'currency' => config('commission.currency', 'usd'),
                        'destination' => $vendor->stripe_account_id,
                        'metadata' => ['payout_id' => $payout->id],
                    ]);

                    $payout->transaction_id = $transfer->id;
                    $payout->status = PayoutStatus::PAID;
                    $payout->save();

                    // Mark earnings as paid
                    $vendor->vendorEarnings()->whereIn('id', $unpaidEarnings->pluck('id'))->update([
                        'is_paid' => true,
                        'payout_id' => $payout->id
                    ]);

                    Log::info("Payout {$payout->id} created for vendor {$vendor->id}");
                } catch (Throwable $e) {
                    Log::error("Stripe transfer failed for payout {$payout->id}: " . $e->getMessage());
                    $payout->status = PayoutStatus::FAILED;
                    $payout->payment_details = json_encode(['error' => $e->getMessage()]);
                    $payout->save();
                }
            });
        }
    }
}
