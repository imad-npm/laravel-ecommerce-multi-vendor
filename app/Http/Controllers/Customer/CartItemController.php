<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product; // Only Product is needed for store method
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getAuthenticatedCart(Auth::user());

        return view('customer.cart.index', compact('cart'));
    }

    public function store(Request $request, Product $product)
    {
        $qty = $request->input('quantity', 1);
        $this->cartService->addAuthenticatedProductToCart(Auth::user(), $product, $qty);

        return back()->with('success', 'Added to your cart.');
    }

    public function update(Request $request, int $productId)
    {
        $this->cartService->updateAuthenticatedCartItem(Auth::user(), $productId, $request->input('quantity'));
        return back()->with('success', 'Cart updated.');
    }

    public function destroy(int $productId)
    {
        $this->cartService->removeAuthenticatedCartItem(Auth::user(), $productId);
        return back()->with('success', 'Item removed from cart.');
    }
}