<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class CancelStaleOrders extends Command
{
    protected $signature = 'orders:cancel-stale';
    protected $description = 'Cancel orders that are still pending after 1 day';

    public function handle()
    {
        $cutoff = now()->subDay();
        $orders = Order::where('status', 'pending')
            ->where('created_at', '<', $cutoff)
            ->get();
        $count = 0;
        foreach ($orders as $order) {
            $order->update(['status' => 'cancelled']);
            $count++;
        }
        \Log::info("Cancelled $count stale orders at " . now());
        $this->info("Cancelled $count stale orders.");
    }
}
