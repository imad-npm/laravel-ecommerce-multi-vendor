<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\ProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {}

    public function index(Request $request)
    {
        $products = $this->productService->getProducts($request);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $productData = ProductData::fromRequest($request);
        $this->productService->updateProduct($product, $productData);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }
}