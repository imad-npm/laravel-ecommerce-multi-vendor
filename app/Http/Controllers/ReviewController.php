<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //
    public function store(Request $request, Product $product)
{
    $user = auth()->user();

    // Vérifie que l'utilisateur a acheté le produit
    if (!$user->hasPurchased($product)) {
        abort(403, 'You can only review products you have purchased.');
    }

    $request->validate([
        'stars' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $user->reviews()->updateOrCreate(
        ['product_id' => $product->id],
        $request->only('stars', 'comment')
    );

    return back()->with('success', 'Thanks for your review!');
}

}
