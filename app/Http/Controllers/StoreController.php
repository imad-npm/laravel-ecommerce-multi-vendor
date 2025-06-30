<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show(Store $store)
    {
        $store->load(['products', 'user']);
        // Calculate average rating for all products in the store
        $avgRating = $store->averageRating();
        return view('stores.show', compact('store', 'avgRating'));
    }
}
