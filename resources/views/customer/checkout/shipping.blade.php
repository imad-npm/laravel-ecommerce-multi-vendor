@extends('layouts.checkout')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Shipping Information</h2>
    <form action="{{ route('customer.checkout.shipping.process') }}" method="POST">
        @csrf

        @if($shippingAddresses->count() > 0)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-3">Select a Saved Address</h3>
                <div class="space-y-3">
                    @foreach($shippingAddresses as $address)
                        <label class="flex items-center p-4 border rounded-md cursor-pointer hover:bg-neutral-50">
                            <x-ui.radio name="selected_address_id" :value="$address->id"
                                :checked="(isset($prefillAddress) && is_object($prefillAddress) && $prefillAddress->id === $address->id)" />
                            <span class="ml-3 text-neutral-700">
                                {{ $address->address_line_1 }}, {{ $address->city }}, {{ $address->postal_code }}
                            </span>
                        </label>
                    @endforeach
                    <label class="flex items-center p-4 border rounded-md cursor-pointer hover:bg-neutral-50">
                        <x-ui.radio name="selected_address_id" value="new"
                            :checked="!isset($prefillAddress) || (is_array($prefillAddress) && !isset($prefillAddress['shipping_address_id']))" />
                        <span class="ml-3 text-neutral-700">Enter a new address</span>
                    </label>
                </div>
            </div>
        @endif

        <div id="new-address-fields" class="{{ $shippingAddresses->count() > 0 && (isset($prefillAddress) && isset($prefillAddress->id)) ? 'hidden' : '' }}">
            <h3 class="text-lg font-medium text-neutral-900 mb-3">{{ $shippingAddresses->count() > 0 ? 'Or Enter New Address' : 'Enter Shipping Address' }}</h3>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <x-ui.input-label for="address_line_1" value="Address Line 1" />
                    <x-ui.input type="text" id="address_line_1" name="address_line_1" :value="old('address_line_1', is_array($prefillAddress) ? ($prefillAddress['address_line_1'] ?? '') : ($prefillAddress->address_line_1 ?? ''))" class="mt-1 block w-full" />
                    <x-ui.input-error :messages="$errors->get('address_line_1')" class="mt-2" />
                </div>
                <div>
                    <x-ui.input-label for="city" value="City" />
                    <x-ui.input type="text" id="city" name="city" :value="old('city', is_array($prefillAddress) ? ($prefillAddress['city'] ?? '') : ($prefillAddress->city ?? ''))" class="mt-1 block w-full" />
                    <x-ui.input-error :messages="$errors->get('city')" class="mt-2" />
                </div>
                <div>
                    <x-ui.input-label for="postal_code" value="Postal Code" />
                    <x-ui.input type="text" id="postal_code" name="postal_code" :value="old('postal_code', is_array($prefillAddress) ? ($prefillAddress['postal_code'] ?? '') : ($prefillAddress->postal_code ?? ''))" class="mt-1 block w-full" />
                    <x-ui.input-error :messages="$errors->get('postal_code')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-between">
            <x-ui.link href="{{ route('cart.index') }}" variant="default">Back to Cart</x-ui.link>
            <x-ui.button type="submit" variant="primary">Continue to Payment</x-ui.button>
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