<?php

namespace App\Services\Cart;

use App\DataTransferObjects\CartItem\CreateCartItemDTO;
use App\DataTransferObjects\CartItem\UpdateCartItemDTO;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

use Illuminate\Database\Eloquent\Collection;

class CustomerCartService
{
    public function getCartDetails(): Collection
    {
        $user = auth()->user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart) {
            return new Collection();
        }

        // Filter out items where the product might have been deleted
        return $cart->items->filter(fn($item) => $item->product);
    }

public function addItemToCart( CreateCartItemDTO $data): void
{
    $user=auth()->user() ;

    $cart = $user->cart ?? $user->cart()->create();
    $cartItem = $cart->items()->firstOrNew(['product_id' => $data->productId]);
    $cartItem->quantity += $data->quantity;
    $cartItem->save();
}


    public function updateItemQuantity( CartItem $cartItem, UpdateCartItemDTO $data): void
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
public static function itemsCount(): int
{
        $user=auth()->user() ;
        $cart = Cart::with('items')->where('user_id', $user->id)->first();
        return $cart?->items->sum('quantity') ?? 0;
   
   
}

}
