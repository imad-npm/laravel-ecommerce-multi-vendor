<?php

namespace App\DataTransferObjects;

use App\Models\User;
use Spatie\LaravelData\Data;

class PayoutData extends Data
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
