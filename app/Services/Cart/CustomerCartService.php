<?php

namespace App\Services\Cart;

use App\DataTransferObjects\CartItemData;
use App\DataTransferObjects\CartItem\CreateCartItemData;
use App\DataTransferObjects\CartItem\UpdateCartItemData;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class CustomerCartService
{
    public function getCartDetails(): object
    {
        $user=auth()->user() ;
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart) {
            return (object) [
                'items' => collect(),
                'total' => 0,
            ];
        }

        $items = $cart->items->map(function ($item) {
            if (!$item->product) {
                return null;
            }
            return (object) [
                'product' => $item->product,
                'quantity' => $item->quantity,
                'product_id' => $item->product_id,
            ];
        })->filter()->values();

        return (object) [
            'items' => $items,
            'total' => $items->sum(fn($item) => $item->product->price * $item->quantity),
        ];
    }

public function addItemToCart( CreateCartItemData $data): void
{
    $user=auth()->user() ;

    $cart = $user->cart ?? $user->cart()->create();
    $cartItem = $cart->items()->firstOrNew(['product_id' => $data->productId]);
    $cartItem->quantity += 1;
    $cartItem->save();
}


    public function updateItemQuantity( CartItem $cartItem, UpdateCartItemData $data): void
    {
      
        if ($data->quantity > 0) {
            $cartItem->update(['quantity' => $data->quantity]);
        } else {
            $cartItem->delete();
        }
    }

    public function removeItemFromCart( CartItem $cartItem): void
    {
       
        $cartItem->delete();
    }

    public function clearAllItems(User $user): void
    {
        $user->cart?->items()->delete();
    }
}
