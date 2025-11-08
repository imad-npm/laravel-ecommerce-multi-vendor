<?php

namespace App\Services;

use App\DataTransferObjects\StoreData;
use App\Models\Store;
use App\Models\User; // Added
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class StoreService
{
    public function getAllStores(Request $request)
    {
        $query = Store::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        return $query->paginate(10);
    }

    // Unified createStore method
    public function createStore(StoreData $data, User $user): Store
    {
        $storeData = $data->toArray();

        if ($data->logo) {
            $storeData['logo'] = $data->logo->store('stores', 'public'); // Unified path
        }

        $storeData['user_id'] = $user->id; // Assign user_id

        return Store::create($storeData);
    }

    public function updateStore(Store $store, StoreData $data): bool
    {
        $storeData = $data->toArray();

        if ($data->logo) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $storeData['logo'] = $data->logo->store('stores', 'public'); // Unified path
        }

        return $store->update($storeData);
    }

    public function deleteStore(Store $store): bool
    {
        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }
        return $store->delete();
    }
}
