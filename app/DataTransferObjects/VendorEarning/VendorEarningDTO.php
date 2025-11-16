<?php

namespace App\DataTransferObjects\VendorEarning;

use App\Models\Order;
use App\Models\User;

final readonly class VendorEarningDTO
{
    public function __construct(
        public ?int $id,
        public int $vendor_id,
        public int $order_id,
        public float $total_amount,
        public float $commission,
        public float $net_earnings,
        public bool $is_paid,
        public ?int $payout_id = null,
        public ?User $vendor = null,
        public ?Order $order = null,
    ) {}

    /**
     * Create DTO from validated request data
     */
    public static function fromArray(array $data): self
    {

        return new self(
            id: $data['id'] ?? null,
            vendor_id: $data['vendor_id'],
            order_id: $data['order_id'],
            total_amount: (float) ($data['total_amount'] ?? 0),
            commission: (float) ($data['commission'] ?? 0),
            net_earnings: (float) ($data['net_earnings'] ?? 0),
            is_paid: (bool) ($data['is_paid'] ?? false),
            payout_id: $data['payout_id'] ?? null,
        );
    }
}
