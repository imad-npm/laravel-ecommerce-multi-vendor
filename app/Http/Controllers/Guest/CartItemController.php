<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Not used in guest controller, but good to have for consistency if needed later

class CartItemController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getGuestCart();

        return view('guest.cart.index', compact('cart'));
    }

    public function store(Request $request, Product $product)
    {
        $qty = $request->input('quantity', 1);
        $this->cartService->addGuestProductToCart($product, $qty); // Pass null for user

        return back()->with('success', 'Added to your cart.');
    }

    public function update(Request $request, int $productId)
    {
        $this->cartService->updateGuestCartItem($productId, $request->input('quantity')); // Pass null for user
        return back()->with('success', 'Cart updated.');
    }

    public function destroy(int $productId)
    {
        $this->cartService->removeGuestCartItem($productId); // Pass null for user
        return back()->with('success', 'Item removed from cart.');
    }
}
