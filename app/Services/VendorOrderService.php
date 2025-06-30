<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\User;

class VendorOrderService
{
    public function getOrderItemsForAuthenticatedStore(User $vendor)
    {
        $store = $vendor->store;

        if (!$store) {
            return collect(); // Return an empty collection if no store is found
        }

        return OrderItem::with(['order', 'product', 'order.customer'])
            ->whereHas('product', function ($query) use ($store) {
                $query->where('store_id', $store->id);
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
