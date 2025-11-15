<?php

namespace App\DataTransferObjects\Category;

use Illuminate\Http\Request;

class CategoryDTO
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
