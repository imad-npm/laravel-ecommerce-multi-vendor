<?php // app/Listeners/MergeGuestCart.php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Session;
use App\Services\CartService;

class MergeGuestCart
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function handle(Authenticated $event): void
    {
        $guestCart = Session::pull('guest_cart', []);

        foreach ($guestCart as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            if ($product) {
                $this->cartService->addProductToCart($product, $item['quantity']);
            }
        }
    }
}
