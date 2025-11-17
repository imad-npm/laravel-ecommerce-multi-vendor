<?php

namespace App\Services\Cart;

use App\DataTransferObjects\CartItem\CreateCartItemDTO;
use App\DataTransferObjects\CartItem\UpdateCartItemDTO;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class GuestCartService
{
    public function getCartDetails(): object
    {
        $guestCartData = Session::get('guest_cart', []);
        $validGuestCartData = [];

        $items = collect($guestCartData)->map(function ($itemData) use (&$validGuestCartData) {
            $product = Product::find($itemData['product_id']);
            if ($product) {
                $validGuestCartData[$product->id] = $itemData;
                return (object) [
                    'product' => $product,
                    'quantity' => $itemData['quantity'],
                    'product_id' => $product->id,
                ];
            }
            return null;
        })->filter();

        Session::put('guest_cart', $validGuestCartData);

        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);

        return (object) [
            'items' => $items,
            'total' => $total,
        ];
    }

  public function addItemToCart(CreateCartItemDTO $data): void
{
   
    $cart = Session::get('guest_cart', []);
    $key = $data->productId;

    $cart[$key] = [
        'product_id' => $data->productId,
        'quantity' => ($cart[$key]['quantity'] ?? 0) + $data->quantity,
    ];

    Session::put('guest_cart', $cart);
}


    public function updateItemQuantity(int $productId,UpdateCartItemDTO $data): void
    {
        $cart = Session::get('guest_cart', []);
        if (isset($cart[$productId])) {
            if ($data->quantity > 0) {
                $cart[$productId]['quantity'] = $data->quantity;
            } else {
                unset($cart[$productId]);
            }
        }
        Session::put('guest_cart', $cart);
    }

    public function removeItemFromCart(int $productId): void
    {
        $cart = Session::get('guest_cart', []);
        unset($cart[$productId]);
        Session::put('guest_cart', $cart);
    }

    public function clearAllItems(): void
    {
        Session::forget('guest_cart');
    }

    public static function itemsCount(): int
{
    
    $cartItems = session('guest_cart', []);
    return collect($cartItems)->sum('quantity');
}
}
