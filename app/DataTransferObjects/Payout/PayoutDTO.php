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

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            vendor_id: $data['vendor_id'],
            amount: $data['amount'],
            status: $data['status'],
            transaction_id: $data['transaction_id'] ?? null,
            vendor: $data['vendor'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'transaction_id' => $this->transaction_id,
        ];
    }
}
