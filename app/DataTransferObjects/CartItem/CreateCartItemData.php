<?php
namespace App\DataTransferObjects\CartItem;

class CreateCartItemData
{
    public int $productId;

    public function __construct( int $productId )
    {
        $this->productId = $productId;
    }

    public static function from(array $data): self
    {
        return new self(
            $data['product_id'] 
        );
    }
}
