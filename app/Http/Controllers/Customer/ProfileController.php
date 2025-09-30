<?php

namespace App\Http\Controllers\Customer;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProfileController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('customer.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('customer.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display a list of shipping addresses.
     */
    public function showAddresses(Request $request): View
    {
        return view('customer.profile.addresses.index', [
            'addresses' => $request->user()->shippingAddresses,
        ]);
    }

    /**
     * Show the form for creating a new shipping address.
     */
    public function createAddress(): View
    {
        return view('customer.profile.addresses.create');
    }

    /**
     * Store a newly created shipping address in storage.
     */
    public function storeAddress(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
        ]);

        $request->user()->shippingAddresses()->create($validated);

        return Redirect::route('customer.profile.addresses.index')->with('status', 'address-created');
    }

    /**
     * Show the form for editing the specified shipping address.
     */
    public function editAddress(ShippingAddress $address): View
    {
        $this->authorize('update', $address);

        return view('customer.profile.addresses.edit', compact('address'));
    }

    /**
     * Update the specified shipping address in storage.
     */
    public function updateAddress(Request $request, ShippingAddress $address): RedirectResponse
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
        ]);

        $address->update($validated);

        return Redirect::route('customer.profile.addresses.index')->with('status', 'address-updated');
    }

    /**
     * Remove the specified shipping address from storage.
     */
    public function destroyAddress(ShippingAddress $address): RedirectResponse
    {
        $this->authorize('delete', $address);

        $address->delete();

        return Redirect::route('customer.profile.addresses.index')->with('status', 'address-deleted');
    }
}
