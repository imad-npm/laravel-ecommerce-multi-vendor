<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;
    protected $chatService;

    public function __construct(ProductService $productService, ChatService $chatService)
    {
        $this->productService = $productService;
        $this->chatService = $chatService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->getProducts($request);
        $categories = Category::all();
        
        return view('customer.products.index', compact('products', 'categories'));
    }
    

    public function show(Product $product)
    {
        $reviews = $product->reviews()->latest()->get();
        $conversation = null;

        if (Auth::check() && Auth::id() !== $product->store->user->id) {
            $conversation = $this->chatService->findConversation(Auth::user(), $product->store->user, $product);
        }

        return view('customer.products.show', compact('product', 'reviews', 'conversation'));
    }
}
