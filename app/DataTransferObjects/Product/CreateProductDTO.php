<?php

namespace App\DataTransferObjects\Product;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class CreateProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly float $price,
        public readonly int $stock,
        public readonly int $category_id,
        public readonly UploadedFile $image,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            price: $data['price'],
            stock: $data['stock'],
            category_id: $data['category_id'],
            image: $data['image'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
        ];
    }
}
