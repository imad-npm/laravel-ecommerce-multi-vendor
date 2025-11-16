<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Review\StoreReviewRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewController extends Controller
{
    use AuthorizesRequests ;
    //
    public function store(StoreReviewRequest $request, Product $product)
{

        $this->authorize('create', $product);

    $user = auth()->user();

    $user->reviews()->updateOrCreate(
        ['product_id' => $product->id],
        $request->only('stars', 'comment')
    );

    return back()->with('success', 'Thanks for your review!');
}

}
