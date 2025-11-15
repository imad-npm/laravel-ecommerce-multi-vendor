<?php

namespace App\DataTransferObjects\Order;

use Illuminate\Http\Request;

class OrderData
{
    public function __construct(
        public readonly string $status,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            status: $request->input('status'),
        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
        ];
    }
}
