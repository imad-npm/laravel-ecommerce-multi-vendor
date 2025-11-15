<?php

namespace App\Services;

use App\DataTransferObjects\VendorEarning\VendorEarningData;
use App\Models\Order;
use App\Models\VendorEarning;
use Illuminate\Pagination\LengthAwarePaginator;

class VendorEarningService
{
    public function getAllVendorEarnings(): LengthAwarePaginator
    {
        return VendorEarning::with(['vendor', 'order'])->latest()->paginate(10);
    }

    public function createVendorEarnings(Order $order): void
    {
        $commissionRate = config('commission.rate');

        foreach ($order->items as $item) {
            $vendor = $item->product->store->user;
            $totalAmount = $item->price * $item->quantity;
            $commission = $totalAmount * $commissionRate;
            $netEarnings = $totalAmount - $commission;

            VendorEarning::create([
                'vendor_id' => $vendor->id,
                'order_id' => $order->id,
                'total_amount' => $totalAmount,
                'commission' => $commission,
                'net_earnings' => $netEarnings,
                'is_paid' => false,
            ]);
        }
    }

    public function updateVendorEarning(VendorEarning $vendorEarning, VendorEarningData $vendorEarningData): bool
    {
        return $vendorEarning->update($vendorEarningData->all());
    }

    public function getEligibleVendorEarningsForPayout()
    {
        return VendorEarning::where('is_paid', false)
            ->whereNull('payout_id')
            ->with('vendor')
            ->get();
    }
}