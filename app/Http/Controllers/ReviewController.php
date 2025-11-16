<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Review\StoreReviewRequest;

class ReviewController extends Controller
{
    //
    public function store(StoreReviewRequest $request, Product $product)
{
    $user = auth()->user();

    // Vérifie que l'utilisateur a acheté le produit
    if (!$user->hasPurchased($product)) {
        abort(403, 'You can only review products you have purchased.');
    }

    $user->reviews()->updateOrCreate(
        ['product_id' => $product->id],
        $request->only('stars', 'comment')
    );

    return back()->with('success', 'Thanks for your review!');
}

}
