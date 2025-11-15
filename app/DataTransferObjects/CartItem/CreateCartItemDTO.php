<?php

namespace App\DataTransferObjects\CartItem;

class CreateCartItemDTO
{
    public int $productId;
    public int $quantity;

    public function __construct(int $productId, int $quantity = 1)
    {
        $this->productId = $productId;
        $this->quantity  = max(1, $quantity); // safety: avoid 0 or negative
    }

    public static function from(array $data): self
    {
        return new self(
            $data['product_id'],
            $data['quantity'] ?? 1  // default = 1
        );
    }
}
