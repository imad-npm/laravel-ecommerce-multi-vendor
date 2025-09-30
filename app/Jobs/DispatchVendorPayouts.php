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

class DispatchVendorPayouts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
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

                if ($totalUnpaid >= $threshold) {
                    $payout = Payout::create([
                        'vendor_id' => $vendor->id,
                        'amount' => $totalUnpaid,
                        'status' => 'pending',
                        'payment_method' => 'auto', // Or a default method
                        'payment_details' => json_encode(['note' => 'Auto-generated payout']),
                    ]);

                    // Simulate payment processing (replace with actual API calls)
                    // For now, we'll just mark it as completed immediately for demonstration
                    $payout->status = 'completed';
                    $payout->save();

                    // Mark these earnings as paid
                    $vendor->vendorEarnings()->whereIn('id', $unpaidEarnings->pluck('id'))->update(['is_paid' => true]);
                }
            });
        }
    }
}
