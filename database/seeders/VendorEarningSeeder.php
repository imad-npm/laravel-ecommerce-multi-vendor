<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\VendorEarning;
use Illuminate\Database\Seeder;

class VendorEarningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::where('status', 'paid')->with('items.product.store.user')->get();
        $commissionRate = config('commission.rate') / 100;

        $orders->each(function ($order) use ($commissionRate) {
            foreach ($order->items as $item) {
                if ($item->product && $item->product->store && $item->product->store->user) {
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
        });
    }
}
