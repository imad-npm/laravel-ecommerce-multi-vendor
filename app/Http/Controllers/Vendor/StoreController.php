<?php
namespace App\Http\Controllers\Vendor;

use App\DataTransferObjects\Store\StoreDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreRequest;
use App\Services\StoreService; // Modified
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __construct(protected StoreService $storeService) // Modified
    {}

    public function create()
    {
        $user = Auth::user();
        if ($user->store) {
            return redirect()->route('vendor.dashboard')->with('error', 'You already have a store.');
        }

        return view('vendor.store.create');
    }

    public function store(StoreRequest $request)
    {
        $user = Auth::user();

        if ($user->store) {
            return redirect()->route('vendor.dashboard')->with('error', 'Store already exists.');
        }

        $storeData = StoreDTO::fromRequest($request); // Modified
        $this->storeService->createStore($storeData, $user); // Modified

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

    public function update(StoreRequest $request)
    {
        $store = Auth::user()->store;

        $storeData = StoreDTO::fromRequest($request); // Modified
        $this->storeService->updateStore($store, $storeData); // Modified

        return redirect()->route('vendor.dashboard')->with('success', 'Store updated.');
    }

    public function destroy()
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return redirect()->route('vendor.dashboard')->with('error', 'No store to delete.');
        }

        $this->storeService->deleteStore($store); // Modified

        return redirect()->route('vendor.dashboard')->with('success', 'Store deleted.');
    }
}
