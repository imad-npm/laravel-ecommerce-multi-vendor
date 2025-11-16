<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\Cart\CustomerCartService;
use App\DataTransferObjects\CartItem\CreateCartItemDTO;
use App\DataTransferObjects\CartItem\UpdateCartItemDTO;
use App\Http\Requests\CartItem\StoreCartItemRequest;
use App\Http\Requests\CartItem\UpdateCartItemRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartItemController extends Controller
{
    public function __construct(protected CustomerCartService $customerCartService)
    {
    }

    /**
     * Display a listing of the customer's cart items.
     */
    public function index(): View
    {
        $cartItems = $this->customerCartService->getCartDetails();

        return view('customer.cart.index', compact('cartItems'));
    }

    /**
     * Store a newly created cart item in storage for the authenticated customer.
     */
    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $this->customerCartService->addItemToCart( CreateCartItemDTO::from($request->validated()));

        return redirect()
            ->route('customer.cart-items.index')
            ->with('success', 'Product added to cart!');
    }

    /**
     * Update the specified cart item in storage for the authenticated customer.
     */
    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        $this->customerCartService->updateItemQuantity($cartItem, UpdateCartItemDTO::from($request->validated()));

        return redirect()
            ->route('customer.cart-items.index')
            ->with('success', 'Cart item updated!');
    }

    /**
     * Remove the specified cart item from storage for the authenticated customer.
     */
    public function destroy(CartItem $cartItem): RedirectResponse
    {

        $this->customerCartService->removeItemFromCart($cartItem);

        return redirect()
            ->route('customer.cart-items.index')
            ->with('success', 'Product removed from cart!');
    }

  
}
