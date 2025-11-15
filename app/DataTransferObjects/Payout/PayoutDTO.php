<?php

namespace App\DataTransferObjects\Payout;

use App\Models\User;

class PayoutDTO 
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $vendor_id,
        public readonly float $amount,
        public readonly string $status,
        public readonly ?string $transaction_id,
        public readonly ?User $vendor,
    ) {
    }
}
