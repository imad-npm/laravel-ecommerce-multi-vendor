<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->getProducts($request);
        $categories = Category::all();
        
        return view('customer.products.index', compact('products', 'categories'));
    }
    

public function show(Product $product)
{
    $reviews = $product->reviews()->latest()->get(); // or whatever logic you want
    return view('customer.products.show', compact('product', 'reviews'));
}
}
