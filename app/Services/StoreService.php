<?php

namespace App\Services;

use App\DataTransferObjects\StoreData;
use App\Models\Store;
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

    public function createStore(StoreData $data): Store
    {
        $storeData = $data->toArray();

        if ($data->logo) {
            $storeData['logo'] = $data->logo->store('store_logos', 'public');
        }

        return Store::create($storeData);
    }

    public function updateStore(Store $store, StoreData $data): bool
    {
        $storeData = $data->toArray();

        if ($data->logo) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $storeData['logo'] = $data->logo->store('store_logos', 'public');
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
