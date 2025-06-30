<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = auth()->check()
            ? $this->cartService->getCartForUser(Auth::user())
            : $this->cartService->getGuestCart();

        return view('customer.cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $qty = $request->input('quantity', 1);
        $this->cartService->addProductToCart(Auth::user(), $product, $qty);

        return back()->with('success', 'Added to your cart.');
    }

    public function update(Request $request, CartItem $item)
    {
        $this->cartService->updateCartItem($item, $request->input('quantity'));
        return back()->with('success', 'Cart updated.');
    }

    public function destroy(CartItem $item)
    {
        $this->cartService->removeCartItem($item);
        return back()->with('success', 'Item removed from cart.');
    }

    public function updateGuest(Request $request, $productId)
    {
        $this->cartService->updateGuestCartItem($productId, (int) $request->input('quantity', 1));
        return back();
    }

    public function removeGuest($productId)
    {
        $this->cartService->removeGuestCartItem($productId);
        return back();
    }
}