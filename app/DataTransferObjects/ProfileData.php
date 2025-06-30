<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class ProfileData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
