<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ShippingAddress\StoreShippingAddressRequest;
use App\Http\Requests\ShippingAddress\UpdateShippingAddressRequest;

class ShippingAddressController extends Controller
{
    public function index()
    {
        $shippingAddresses = Auth::user()->shippingAddresses;
        return view('customer.addresses.index', compact('shippingAddresses'));
    }

    public function create()
    {
        return view('customer.addresses.create');
    }

    public function store(StoreShippingAddressRequest $request)
    {
        Auth::user()->shippingAddresses()->create($request->validated());

        if ($request->query('redirect') === 'checkout') {
            return redirect()->route('customer.orders.create')->with('success', 'Shipping address added successfully.');
        }

        return redirect()->route('customer.addresses.index')->with('success', 'Shipping address added successfully.');
    }

    public function edit(ShippingAddress $address)
    {
        $this->authorize('update', $address);
        return view('customer.addresses.edit', compact('address'));
    }

    public function update(UpdateShippingAddressRequest $request, ShippingAddress $address)
    {
        $this->authorize('update', $address);

        $address->update($request->validated());

        if ($request->query('redirect') === 'checkout') {
            return redirect()->route('customer.orders.create')->with('success', 'Shipping address updated successfully.');
        }

        return redirect()->route('customer.addresses.index')->with('success', 'Shipping address updated successfully.');
    }

    public function destroy(ShippingAddress $address)
    {
        $this->authorize('delete', $address);
        $address->delete();

        return redirect()->route('customer.addresses.index')->with('success', 'Shipping address deleted successfully.');
    }
}
