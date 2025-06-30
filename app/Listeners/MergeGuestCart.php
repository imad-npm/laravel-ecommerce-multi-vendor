<?php // app/Listeners/MergeGuestCart.php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Session;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;

class MergeGuestCart
{
    public function handle(Authenticated $event): void
    {
        $user      = $event->user;
        $guestCart = Session::pull('guest_cart', []);

        foreach ($guestCart as $item) {
            $user->cart
                 ->items()
                 ->updateOrCreate(
                     ['product_id' => $item['product_id']],
                     ['quantity'   => DB::raw("quantity + {$item['quantity']}")]
                 );
        }
    }
}
