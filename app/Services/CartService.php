<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartService
{
    // Authenticated User Cart Methods
    public function getAuthenticatedCart(User $user): ?Cart
    {
        return Cart::with('items.product')->where('user_id', $user->id)->first();
    }

    public function addAuthenticatedProductToCart(User $user, Product $product, int $quantity): void
    {
        $cart = $user->cart ?? $user->cart()->create();
        $cartItem = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $cartItem->quantity += $quantity;
        $cartItem->save();
    }

    public function updateAuthenticatedCartItem(User $user, int $productId, int $quantity): void
    {
        $cart = $user->cart;
        if ($cart) {
            $cartItem = $cart->items()->where('product_id', $productId)->first();
            if ($cartItem) {
                $cartItem->update(['quantity' => $quantity]);
            }
        }
    }

    public function removeAuthenticatedCartItem(User $user, int $productId): void
    {
        $cart = $user->cart;
        if ($cart) {
            $cartItem = $cart->items()->where('product_id', $productId)->first();
            if ($cartItem) {
                $cartItem->delete();
            }
        }
    }

    public function clearAuthenticatedCart(User $user): void
    {
        $user->cart->items()->delete();
    }

    // Guest User Cart Methods
    public function getGuestCart(): object
    {
        $guestCartData = Session::get('guest_cart', []);
        $validGuestCartData = [];
        $items = collect($guestCartData)->map(function ($itemData) use (&$validGuestCartData) {
            $product = Product::find($itemData['product_id']);
            // dd($itemData, $product); // Temporary debug statement
            if ($product) {
                $validGuestCartData[$product->id] = $itemData; // Keep only valid items
                return (object) [
                    'product' => $product,
                    'quantity' => $itemData['quantity'],
                ];
            }
            return null;
        })->filter();

        // Update the session with only valid cart items
        Session::put('guest_cart', $validGuestCartData);

        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);

        return (object) [
            'items' => $items,
            'total' => $total,
        ];
    }

    public function addGuestProductToCart(Product $product, int $quantity): void
    {
        $cart = Session::get('guest_cart', []);
        $key  = $product->id;

        $cart[$key] = [
            'product_id' => $product->id,
            'quantity'   => ($cart[$key]['quantity'] ?? 0) + $quantity,
        ];

        Session::put('guest_cart', $cart);
    }

    public function updateGuestCartItem(int $productId, int $quantity): void
    {
        $cart = Session::get('guest_cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
        }
        Session::put('guest_cart', $cart);
    }

    public function removeGuestCartItem(int $productId): void
    {
        $cart = Session::get('guest_cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }
        Session::put('guest_cart', $cart);
    }

    public function clearGuestCart(): void
    {
        Session::forget('guest_cart');
    }
}
