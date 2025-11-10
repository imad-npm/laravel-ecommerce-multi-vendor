<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\ConversationService;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;
    protected $conversationService;

    public function __construct(ProductService $productService, ConversationService $conversationService)
    {
        $this->productService = $productService;
        $this->conversationService = $conversationService;
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
            $conversation = $this->conversationService->findConversation(Auth::user(), $product->store->user, $product);
        }

        return view('customer.products.show', compact('product', 'reviews', 'conversation'));
    }
}
