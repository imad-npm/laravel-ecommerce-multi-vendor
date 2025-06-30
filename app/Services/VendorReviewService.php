<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;

class VendorReviewService
{
    public function getReviewsForAuthenticatedStore(User $vendor)
    {
        $store = $vendor->store;

        if (!$store) {
            return collect();
        }

        return Review::whereHas('product', function ($query) use ($store) {
            $query->where('store_id', $store->id);
        })
        ->with(['product', 'user'])
        ->orderByDesc('created_at')
        ->get();
    }
}
