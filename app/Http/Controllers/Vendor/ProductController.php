<?php

namespace App\Http\Controllers\Vendor;

use App\DataTransferObjects\VendorProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorProductRequest;
use App\Models\Product;
use App\Services\VendorProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct(protected VendorProductService $vendorProductService)
    {}

    /**
     * Liste tous les produits du store du vendeur connecté.
     */
    public function index()
    {
        $store = Auth::user()->store;
        if (!$store) {
            return redirect()->route('vendor.store.create')->with('error', 'You need to create a store first to manage products.');
        }
        $products = $this->vendorProductService->getProductsForAuthenticatedStore($store);
        return view('vendor.products.index', compact('products'));
    }

    /**
     * Affiche le formulaire de création d’un produit.
     */
    public function create()
    {
        $store = Auth::user()->store;
        if (!$store) {
            return redirect()->route('vendor.store.create')->with('error', 'You need to create a store first to create products.');
        }
        return view('vendor.products.create');
    }

    /**
     * Enregistre un nouveau produit dans la base.
     */
    public function store(VendorProductRequest $request)
    {
        $store = Auth::user()->store;
        if (!$store) {
            return redirect()->route('vendor.store.create')->with('error', 'You need to create a store first to add products.');
        }
        $productData = VendorProductData::fromRequest($request);
        $this->vendorProductService->createProduct($store, $productData);

        return redirect()->route('vendor.dashboard')->with('success', 'Product added.');
    }

    /**
     * Affiche le formulaire d’édition d’un produit.
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);
        return view('vendor.products.edit', compact('product'));
    }

    /**
     * Met à jour les informations d’un produit.
     */
    public function update(VendorProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);
        $productData = VendorProductData::fromRequest($request);
        $this->vendorProductService->updateProduct($product, $productData);

        return redirect()->route('vendor.dashboard')->with('success', 'Product updated.');
    }

    public function show(Product $product)
    {
        Gate::authorize('view', $product);
        $product->load('reviews.user'); // Eager load reviews and their associated users
        return view('vendor.products.show', compact('product'));
    }

    /**
     * Supprime un produit.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);
        $this->vendorProductService->deleteProduct($product);

        return redirect()->route('vendor.dashboard')->with('success', 'Product deleted.');
    }
}