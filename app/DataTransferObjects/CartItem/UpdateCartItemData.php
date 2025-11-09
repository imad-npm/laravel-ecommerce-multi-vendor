<?php
namespace App\DataTransferObjects\CartItem;

class UpdateCartItemData
{
    public ?int $productId;
    public int $quantity;

    public function __construct(int $quantity, ?int $productId = null)
    {
        $this->quantity = $quantity;
        $this->productId = $productId;
    }

    public static function from(array $data): self
    {
        return new self(
            $data['quantity'],
            $data['product_id'] ?? null
        );
    }
}
