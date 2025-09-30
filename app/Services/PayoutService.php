<?php

namespace App\Services;

use App\Models\User;
use App\Models\VendorEarning;
use App\Models\Payout;
use Illuminate\Support\Facades\DB;

class PayoutService
{
    public function createPayout(User $vendor, string $paymentMethod, array $paymentDetails)
    {
        $unpaidEarnings = VendorEarning::where('vendor_id', $vendor->id)->where('is_paid', false)->get();

        if ($unpaidEarnings->isEmpty()) {
            return null;
        }

        $payoutAmount = $unpaidEarnings->sum('net_earnings');

        return DB::transaction(function () use ($vendor, $payoutAmount, $paymentMethod, $paymentDetails, $unpaidEarnings) {
            $payout = Payout::create([
                'vendor_id' => $vendor->id,
                'amount' => $payoutAmount,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_details' => json_encode($paymentDetails),
            ]);

            VendorEarning::whereIn('id', $unpaidEarnings->pluck('id'))->update(['is_paid' => true]);

            return $payout;
        });
    }
}
