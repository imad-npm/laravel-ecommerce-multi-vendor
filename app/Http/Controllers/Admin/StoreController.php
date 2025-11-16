<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\Store\StoreDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreRequest;
use App\Models\Store;
use App\Services\StoreService;

use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(protected StoreService $storeService)
    {}

    public function index(Request $request)
    {
        $stores = $this->storeService->getAllStores($request);
        return view('admin.store.index', compact('stores'));
    }

    public function create()
    {
        return view('admin.store.create');
    }

    public function store(StoreRequest $request)
    {
        $storeData = StoreDTO::fromArray($request->validated(), $request->file('logo'));
        $this->storeService->createStore($storeData,auth()->user());
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
        $storeData = StoreDTO::fromArray($request->validated(), $request->file('logo'));
        $this->storeService->updateStore($store, $storeData);
        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy(Store $store)
    {
        $this->storeService->deleteStore($store);
        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully.');
    }
}
