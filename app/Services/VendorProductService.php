<?php

namespace App\Services;

use App\DataTransferObjects\VendorProductData;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorProductService
{
    public function getProductsForAuthenticatedStore($store)
    {
        return $store->products()->withAvg('reviews', 'stars')->latest()->get();
    }

    public function createProduct($store, VendorProductData $data): Product
    {
        $productData = $data->toArray();

        if ($data->image) {
            $productData['image'] = $data->image->store('products', 'public');
        }

        $productData['store_id'] = $store->id;

        return Product::create($productData);
    }

    public function updateProduct(Product $product, VendorProductData $data): bool
    {
        $productData = $data->toArray();

        if ($data->image) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $productData['image'] = $data->image->store('products', 'public');
        }

        return $product->update($productData);
    }

    public function deleteProduct(Product $product): bool
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        return $product->delete();
    }
}
