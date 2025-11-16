<?php

namespace App\Http\Controllers\Vendor;

use App\DataTransferObjects\Product\CreateProductDTO;
use App\DataTransferObjects\Product\UpdateProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\Category; // Added
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View; // Added for return type hinting

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
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
        $products = $this->productService->getProductsForStore($store);
        return view('vendor.products.index', compact('products'));
    }

    /**
     * Affiche le formulaire de création d’un produit.
     */
    public function create() // Added return type
    {
        $store = Auth::user()->store;
        if (!$store) {
            return redirect()->route('vendor.store.create')->with('error', 'You need to create a store first to create products.');
        }
        $categories = Category::all(); // Added
        return view('vendor.products.create', compact('categories')); // Modified
    }

    /**
     * Enregistre un nouveau produit dans la base.
     */
    public function store(StoreProductRequest $request)
    {
        $store = Auth::user()->store;
        if (!$store) {
            return redirect()->route('vendor.store.create')->with('error', 'You need to create a store first to add products.');
        }
        $productData = CreateProductDTO::fromArray($request->validated());
        $this->productService->createProduct($store, $productData);

        return redirect()->route('vendor.dashboard')->with('success', 'Product added.');
    }

    /**
     * Affiche le formulaire d’édition d’un produit.
     */
    public function edit(Product $product): View // Added return type
    {
        Gate::authorize('update', $product);
        $categories = Category::all(); // Added
        return view('vendor.products.edit', compact('product', 'categories')); // Modified
    }

    /**
     * Met à jour les informations d’un produit.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);
        $productData = UpdateProductDTO::fromArray($request->validated());
        $this->productService->updateProduct($product, $productData);

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
        $this->productService->deleteProduct($product);

        return redirect()->route('vendor.dashboard')->with('success', 'Product deleted.');
    }
}
