<?php

namespace App\DataTransferObjects\Product;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class UpdateProductData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly float $price,
        public readonly int $stock,
        public readonly int $category_id,
        public readonly ?UploadedFile $image = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            description: $request->input('description'),
            price: $request->input('price'),
            stock: $request->input('stock'),
            category_id: $request->input('category_id'),
            image: $request->file('image'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
        ];

        return $data;
    }
}
