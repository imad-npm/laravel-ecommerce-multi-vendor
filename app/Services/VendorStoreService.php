<?php

namespace App\Services;

use App\DataTransferObjects\VendorStoreData;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorStoreService
{
    public function createStore(VendorStoreData $data): Store
    {
        $user = Auth::user();
        $storeData = $data->toArray();

        if ($data->logo) {
            $storeData['logo'] = $data->logo->store('logos', 'public');
        }

        $storeData['user_id'] = $user->id;

        return Store::create($storeData);
    }

    public function updateStore(Store $store, VendorStoreData $data): bool
    {
        $storeData = $data->toArray();

        if ($data->logo) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $storeData['logo'] = $data->logo->store('stores', 'public');
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
