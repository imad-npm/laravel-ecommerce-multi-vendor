<?php

namespace App\Services;

use App\DataTransferObjects\CartItemData;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCartForUser($user)
    {
        return Cart::with('items.product')->where('user_id', $user->id)->first();
    }

    public function getGuestCart()
    {
        return Session::get('guest_cart', []);
    }

    public function addProductToCart($user, Product $product, int $quantity)
    {
        if ($user) {
            $cart = $user->cart ?? $user->cart()->create();
            $cartItem = $cart->items()->firstOrNew(['product_id' => $product->id]);
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cart = Session::get('guest_cart', []);
            $key  = $product->id;

            $cart[$key] = [
                'product_id' => $product->id,
                'quantity'   => ($cart[$key]['quantity'] ?? 0) + $quantity,
            ];

            Session::put('guest_cart', $cart);
        }
    }

    public function updateCartItem($item, int $quantity)
    {
        $item->update(['quantity' => $quantity]);
    }

    public function removeCartItem($item)
    {
        $item->delete();
    }

    public function updateGuestCartItem(int $productId, int $quantity)
    {
        $cart = Session::get('guest_cart', []);
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] = $quantity;
                break;
            }
        }
        Session::put('guest_cart', $cart);
    }

    public function removeGuestCartItem(int $productId)
    {
        $cart = Session::get('guest_cart', []);
        $cart = array_filter($cart, fn($item) => $item['product_id'] != $productId);
        Session::put('guest_cart', array_values($cart));
    }
}
