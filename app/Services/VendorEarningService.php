<?php

namespace App\Services;

use App\DataTransferObjects\VendorEarning\VendorEarningDTO;
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

    // Group items by vendor
    $itemsByVendor = $order->items->groupBy(fn($item) =>
        $item->product->store->user_id
    );

    foreach ($itemsByVendor as $vendorId => $items) {
        $total = $items->sum(fn($item) => $item->price * $item->quantity);
        $commission = $total * $commissionRate;
        $net = $total - $commission;

        VendorEarning::create([
            'vendor_id'     => $vendorId,
            'order_id'      => $order->id,
            'total_amount'  => $total,
            'commission'    => $commission,
            'net_earnings'  => $net,
            'is_paid'       => false,
        ]);
    }
}


    public function updateVendorEarning(VendorEarning $vendorEarning, VendorEarningDTO $vendorEarningData): bool
    {
        return $vendorEarning->update($vendorEarningData->toArray());
    }

    public function getEligibleVendorEarningsForPayout()
    {
        return VendorEarning::where('is_paid', false)
            ->whereNull('payout_id')
            ->with('vendor')
            ->get();
    }

    public function getTotalCommission(): float
    {
        return VendorEarning::sum('commission');
    }
}