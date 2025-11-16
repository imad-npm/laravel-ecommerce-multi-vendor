<?php

namespace App\DataTransferObjects\Order;

use Illuminate\Http\Request;

class OrderDTO
{
    public function __construct(
        public readonly string $status,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            status: $data['status'],
        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
        ];
    }
}
