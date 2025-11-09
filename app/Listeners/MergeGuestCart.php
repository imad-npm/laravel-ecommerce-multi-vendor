<?php

namespace App\Listeners;

use App\DataTransferObjects\CartItem\CreateCartItemData;
use Illuminate\Auth\Events\Authenticated;
use App\Services\Cart\GuestCartService;
use App\Services\Cart\CustomerCartService;

class MergeGuestCart
{
    public function __construct(
        protected GuestCartService $guestCartService,
        protected CustomerCartService $customerCartService
    ) {
    }

    public function handle(Authenticated $event): void
    {
        $guestCart = $this->guestCartService->getCartDetails();

        if ($guestCart->items->isEmpty()) {
            return;
        }

        foreach ($guestCart->items as $item) {
          
            $this->customerCartService->addItemToCart(
                CreateCartItemData::from([
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity
                ])
            );
        }

        $this->guestCartService->clearAllItems();
    }
}
