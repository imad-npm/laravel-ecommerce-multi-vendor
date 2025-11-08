<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();

        return view('cart.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $product = Product::findOrFail($request->input('product_id'));
        $qty = $request->input('quantity', 1);
        $this->cartService->addProductToCart($product, $qty);

        return back()->with('success', 'Added to your cart.');
    }

    public function update(Request $request, int $productId)
    {
        $this->cartService->updateCartItem($productId, $request->input('quantity'));
        return back()->with('success', 'Cart updated.');
    }

    public function destroy(int $productId)
    {
        $this->cartService->removeCartItem($productId);
        return back()->with('success', 'Item removed from cart.');
    }
}
