<?php

namespace App\Services;

use App\DataTransferObjects\ProductData;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductService
{
    public function getProducts(Request $request)
    {
        $query = Product::query();

        // Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('store', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                  });
            });
        }
        // Category
        if ($cat = $request->input('category')) {
            $query->where('category_id', $cat);
        }
        // Price range
        if ($min = $request->input('min_price')) {
            $query->where('price', '>=', $min);
        }
        if ($max = $request->input('max_price')) {
            $query->where('price', '<=', $max);
        }
        // Rating
        if ($rating = $request->input('rating')) {
            $query->whereHas('reviews', function($q) use ($rating) {
                $q->havingRaw('AVG(stars) >= ?', [$rating]);
            });
        }
        // In stock only
        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }
        // Sorting
        $sort = $request->input('sort');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'stars')->orderByDesc('reviews_avg_stars');
                break;
            case 'sold':
                $query->orderByDesc('sold_count');
                break;
            default:
                $query->latest();
        }
        return $query->with('reviews')->paginate(12)->withQueryString();
    }

    public function updateProduct(Product $product, ProductData $data): bool
    {
        return $product->update($data->toArray());
    }
}
