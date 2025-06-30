<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class VendorProductData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly float $price,
        public readonly int $stock,
        public readonly ?UploadedFile $image = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            description: $request->input('description'),
            price: $request->input('price'),
            stock: $request->input('stock'),
            image: $request->file('image'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
        ];
    }
}
