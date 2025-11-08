<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart(): object
    {
        if (auth()->check()) {
            return $this->getAuthenticatedUserCart(auth()->user());
        }

        return $this->getGuestUserCart();
    }

    public function addProductToCart(Product $product, int $quantity): void
    {
        if (auth()->check()) {
            $user = auth()->user();
            $cart = $user->cart ?? $user->cart()->create();
            $cartItem = $cart->items()->firstOrNew(['product_id' => $product->id]);
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cart = Session::get('guest_cart', []);
            $key = $product->id;

            $cart[$key] = [
                'product_id' => $product->id,
                'quantity' => ($cart[$key]['quantity'] ?? 0) + $quantity,
            ];

            Session::put('guest_cart', $cart);
        }
    }

    public function updateCartItem(int $productId, int $quantity): void
    {
        if (auth()->check()) {
            $cart = auth()->user()->cart;
            if ($cart) {
                $cartItem = $cart->items()->where('product_id', $productId)->first();
                if ($cartItem) {
                    if ($quantity > 0) {
                        $cartItem->update(['quantity' => $quantity]);
                    } else {
                        $cartItem->delete();
                    }
                }
            }
        } else {
            $cart = Session::get('guest_cart', []);
            if (isset($cart[$productId])) {
                if ($quantity > 0) {
                    $cart[$productId]['quantity'] = $quantity;
                } else {
                    unset($cart[$productId]);
                }
            }
            Session::put('guest_cart', $cart);
        }
    }

    public function removeCartItem(int $productId): void
    {
        if (auth()->check()) {
            auth()->user()->cart?->items()->where('product_id', $productId)->delete();
        } else {
            $cart = Session::get('guest_cart', []);
            unset($cart[$productId]);
            Session::put('guest_cart', $cart);
        }
    }

    public function clearCart(): void
    {
        if (auth()->check()) {
            auth()->user()->cart?->items()->delete();
        } else {
            Session::forget('guest_cart');
        }
    }

    private function getAuthenticatedUserCart(User $user): object
    {
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

    private function getGuestUserCart(): object
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
        })->filter()->values();

        Session::put('guest_cart', $validGuestCartData);

        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);

        return (object) [
            'items' => $items,
            'total' => $total,
        ];
    }
}
