<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\VendorEarning;
use Illuminate\Database\Seeder;

class VendorEarningSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::where('status', 'paid')
            ->with('items.product.store.user')
            ->get();

        $commissionRate = config('commission.rate') / 100;

        $orders->each(function ($order) use ($commissionRate) {

            // Group items by vendor
            $itemsByVendor = $order->items->groupBy(function ($item) {
                return $item->product?->store?->user?->id;
            });

            foreach ($itemsByVendor as $vendorId => $items) {
                if (!$vendorId) {
                    continue; // skip if item has no vendor/owner
                }

                // Calculate totals per vendor
                $totalAmount = $items->sum(fn ($item) => $item->price * $item->quantity);
                $commission  = $totalAmount * $commissionRate;
                $netEarnings = $totalAmount - $commission;

                VendorEarning::create([
                    'vendor_id'     => $vendorId,
                    'order_id'      => $order->id,
                    'total_amount'  => $totalAmount,
                    'commission'    => $commission,
                    'net_earnings'  => $netEarnings,
                    'is_paid'       => false,
                ]);
            }
        });
    }
}
