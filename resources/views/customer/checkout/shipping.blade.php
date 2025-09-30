@extends('layouts.checkout')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Shipping Information</h2>
    <form action="{{ route('customer.checkout.shipping.process') }}" method="POST">
        @csrf

        @if($shippingAddresses->count() > 0)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Select a Saved Address</h3>
                <div class="space-y-3">
                    @foreach($shippingAddresses as $address)
                        <label class="flex items-center p-4 border rounded-md cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="selected_address_id" value="{{ $address->id }}" class="form-radio h-4 w-4 text-blue-600"
                                {{ (isset($prefillAddress) && is_object($prefillAddress) && $prefillAddress->id === $address->id) ? 'checked' : '' }}>
                            <span class="ml-3 text-gray-700">
                                {{ $address->address_line_1 }}, {{ $address->city }}, {{ $address->postal_code }}
                            </span>
                        </label>
                    @endforeach
                    <label class="flex items-center p-4 border rounded-md cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="selected_address_id" value="new" class="form-radio h-4 w-4 text-blue-600"
                            {{ !isset($prefillAddress) || (is_array($prefillAddress) && !isset($prefillAddress['shipping_address_id'])) ? 'checked' : '' }}>
                        <span class="ml-3 text-gray-700">Enter a new address</span>
                    </label>
                </div>
            </div>
        @endif

        <div id="new-address-fields" class="{{ $shippingAddresses->count() > 0 && (isset($prefillAddress) && isset($prefillAddress->id)) ? 'hidden' : '' }}">
            <h3 class="text-lg font-medium text-gray-900 mb-3">{{ $shippingAddresses->count() > 0 ? 'Or Enter New Address' : 'Enter Shipping Address' }}</h3>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="address_line_1" class="block text-sm font-medium text-gray-700">Address Line 1</label>
                    <input type="text" id="address_line_1" name="address_line_1" value="{{ old('address_line_1', is_array($prefillAddress) ? ($prefillAddress['address_line_1'] ?? '') : ($prefillAddress->address_line_1 ?? '')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('address_line_1')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text" id="city" name="city" value="{{ old('city', is_array($prefillAddress) ? ($prefillAddress['city'] ?? '') : ($prefillAddress->city ?? '')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', is_array($prefillAddress) ? ($prefillAddress['postal_code'] ?? '') : ($prefillAddress->postal_code ?? '')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('postal_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-between">
            <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-gray-900">Back to Cart</a>
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">Continue to Payment</button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const savedAddressRadios = document.querySelectorAll('input[name="selected_address_id"]');
            const newAddressFields = document.getElementById('new-address-fields');

            function toggleNewAddressFields() {
                if (document.querySelector('input[name="selected_address_id"]:checked').value === 'new') {
                    newAddressFields.classList.remove('hidden');
                } else {
                    newAddressFields.classList.add('hidden');
                }
            }

            savedAddressRadios.forEach(radio => {
                radio.addEventListener('change', toggleNewAddressFields);
            });

            toggleNewAddressFields(); // Initial call to set visibility based on default checked radio
        });
    </script>
@endsection