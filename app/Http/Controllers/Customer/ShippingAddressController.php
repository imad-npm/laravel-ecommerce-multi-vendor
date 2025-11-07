<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
        ]);

        Auth::user()->shippingAddresses()->create($validated);

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

    public function update(Request $request, ShippingAddress $address)
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
        ]);

        $address->update($validated);

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
