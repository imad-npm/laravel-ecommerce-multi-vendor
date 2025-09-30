<?php

namespace App\Services;

use App\Models\Order;
use App\Models\VendorEarning;

class VendorEarningService
{
    public function createVendorEarnings(Order $order)
    {
        $commissionRate = config('commission.rate');

        foreach ($order->items as $item) {
            $vendor = $item->product->store->user;
            $totalAmount = $item->price * $item->quantity;
            $commission = ($totalAmount * $commissionRate) / 100;
            $netEarnings = $totalAmount - $commission;

            VendorEarning::create([
                'vendor_id' => $vendor->id,
                'order_id' => $order->id,
                'total_amount' => $totalAmount,
                'commission' => $commission,
                'net_earnings' => $netEarnings,
            ]);
        }
    }
}
