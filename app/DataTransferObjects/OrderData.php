<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class OrderData
{
    public function __construct(
        public readonly string $status,
        public readonly string $address,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            status: $request->input('status'),
            address: $request->input('address'),
        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'address' => $this->address,
        ];
    }
}
