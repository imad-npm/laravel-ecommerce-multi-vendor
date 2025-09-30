<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\VendorEarning;
use App\Models\Payout;
use Illuminate\Support\Facades\DB;

class GeneratePayouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payout:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates payouts for vendors who meet the threshold.';

    /**
     * Execute the console command.
     */
    public function handle()
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
                    Payout::create([
                        'vendor_id' => $vendor->id,
                        'amount' => $totalUnpaid,
                        'status' => 'pending',
                        'payment_method' => 'auto', // Or a default method
                        'payment_details' => json_encode(['note' => 'Auto-generated payout']),
                    ]);

                    // Mark these earnings as paid
                    $vendor->vendorEarnings()->whereIn('id', $unpaidEarnings->pluck('id'))->update(['is_paid' => true]);

                    $this->info("Generated payout for vendor {$vendor->id} with amount {$totalUnpaid}");
                }
            });
        }

        $this->info('Payout generation complete.');
    }
}
