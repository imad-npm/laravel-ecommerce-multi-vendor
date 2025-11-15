<?php

namespace App\DataTransferObjects\VendorEarning;

use App\Models\Order;
use App\Models\User;
use Spatie\LaravelData\Data;

class VendorEarningDTO extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $vendor_id,
        public readonly int $order_id,
        public readonly float $total_amount,
        public readonly float $commission,
        public readonly float $net_earnings,
        public readonly bool $is_paid,
        public readonly ?int $payout_id,
        public readonly ?User $vendor,
        public readonly ?Order $order,
    ) {
    }
}
