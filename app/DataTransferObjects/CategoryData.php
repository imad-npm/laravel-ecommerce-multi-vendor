<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class CategoryData
{
    public function __construct(
        public readonly string $name,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
