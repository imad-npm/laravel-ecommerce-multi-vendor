<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Cart\GuestCartService;
use App\DataTransferObjects\CartItemData;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartItemController extends Controller
{
    public function __construct(protected GuestCartService $guestCartService)
    {
    }

    /**
     * Display a listing of the guest cart items.
     */
    public function index(): View
    {
        $cart = $this->guestCartService->getCartDetails();

        return view('cart.index', compact('cart'));
    }

    /**
     * Store a newly created guest cart item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $this->guestCartService->addItemToCart( CartItemData::from($validated));

        return redirect()
            ->route('guest.cart.index')
            ->with('success', 'Product added to cart!');
    }

    /**
     * Update the specified guest cart item in storage.
     */
    public function update(Request $request, int $productId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cartItemData = CartItemData::from(['productId' => $productId, 'quantity' => $validated['quantity']]);

        $this->guestCartService->updateItemQuantity($cartItemData);

        return redirect()
            ->route('guest.cart.index')
            ->with('success', 'Cart item updated!');
    }

    /**
     * Remove the specified guest cart item from storage.
     */
    public function destroy(int $productId): RedirectResponse
    {
        $this->guestCartService->removeItemFromCart($productId);

        return redirect()
            ->route('guest.cart.index')
            ->with('success', 'Product removed from cart!');
    }
}
