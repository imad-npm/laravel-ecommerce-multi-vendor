<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class CartItemData extends Data
{
    public function __construct(
        public int $quantity,
        public ?int $productId = null, // Make productId optional
    ) {}
}