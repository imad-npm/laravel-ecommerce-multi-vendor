<?php
namespace App\Http\Controllers\Vendor;

use App\DataTransferObjects\VendorStoreData;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorStoreRequest;
use App\Services\VendorStoreService;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __construct(protected VendorStoreService $vendorStoreService)
    {}

    public function create()
    {
        $user = Auth::user();
        if ($user->store) {
            return redirect()->route('vendor.dashboard')->with('error', 'You already have a store.');
        }

        return view('vendor.store.create');
    }

    public function store(VendorStoreRequest $request)
    {
        $user = Auth::user();

        if ($user->store) {
            return redirect()->route('vendor.dashboard')->with('error', 'Store already exists.');
        }

        $storeData = VendorStoreData::fromRequest($request);
        $this->vendorStoreService->createStore($storeData);

        return redirect()->route('vendor.dashboard')->with('success', 'Store created.');
    }

    public function show()
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->route('vendor.dashboard')->withErrors('No store found.');
        }

        return view('vendor.store.show', compact('store'));
    }

    public function edit()
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->route('vendor.dashboard')->withErrors('No store found.');
        }

        return view('vendor.store.edit', compact('store'));
    }

    public function update(VendorStoreRequest $request)
    {
        $store = Auth::user()->store;

        $storeData = VendorStoreData::fromRequest($request);
        $this->vendorStoreService->updateStore($store, $storeData);

        return redirect()->route('vendor.dashboard')->with('success', 'Store updated.');
    }

    public function destroy()
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return redirect()->route('vendor.dashboard')->with('error', 'No store to delete.');
        }

        $this->vendorStoreService->deleteStore($store);

        return redirect()->route('vendor.dashboard')->with('success', 'Store deleted.');
    }
}