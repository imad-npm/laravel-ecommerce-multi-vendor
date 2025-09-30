<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Services\VendorEarningService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateVendorEarnings implements ShouldQueue
{
    use InteractsWithQueue;

    protected $vendorEarningService;

    /**
     * Create the event listener.
     */
    public function __construct(VendorEarningService $vendorEarningService)
    {
        $this->vendorEarningService = $vendorEarningService;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPaid $event): void
    {
        $this->vendorEarningService->createVendorEarnings($event->order);
    }
}
