<?php // app/Listeners/MergeGuestCart.php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Session;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;

class MergeGuestCart
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function handle(Authenticated $event): void
    {
        $user      = $event->user;
        $guestCart = Session::pull('guest_cart', []);

        foreach ($guestCart as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            if ($product) {
                $this->cartService->addAuthenticatedProductToCart($user, $product, $item['quantity']);
            }
        }
        $this->cartService->clearGuestCart(); // Clear the guest cart after merging
    }
}
