<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\StoreData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\Store;
use App\Services\StoreService;

class StoreController extends Controller
{
    public function __construct(protected StoreService $storeService)
    {}

    public function index()
    {
        $stores = $this->storeService->getAllStores();
        return view('admin.store.index', compact('stores'));
    }

    public function create()
    {
        return view('admin.store.create');
    }

    public function store(StoreRequest $request)
    {
        $storeData = StoreData::fromRequest($request);
        $this->storeService->createStore($storeData);
        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully.');
    }

    public function show(Store $store)
    {
        return view('admin.store.show', compact('store'));
    }

    public function edit(Store $store)
    {
        return view('admin.store.edit', compact('store'));
    }

    public function update(StoreRequest $request, Store $store)
    {
        $storeData = StoreData::fromRequest($request);
        $this->storeService->updateStore($store, $storeData);
        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy(Store $store)
    {
        $this->storeService->deleteStore($store);
        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully.');
    }
}