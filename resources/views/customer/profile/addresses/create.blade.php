<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Shipping Address') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('customer.profile.addresses.store') }}" class="mt-6 space-y-6">
                        @csrf

                        <div>
                            <label for="address_line_1" class="block font-medium text-sm text-gray-700">Address Line 1</label>
                            <input id="address_line_1" name="address_line_1" type="text" class="mt-1 block w-full" value="{{ old('address_line_1') }}" required autofocus autocomplete="address-line1" />
                            @error('address_line_1')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block font-medium text-sm text-gray-700">City</label>
                            <input id="city" name="city" type="text" class="mt-1 block w-full" value="{{ old('city') }}" required autocomplete="address-level2" />
                            @error('city')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="postal_code" class="block font-medium text-sm text-gray-700">Postal Code</label>
                            <input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full" value="{{ old('postal_code') }}" required autocomplete="postal-code" />
                            @error('postal_code')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Save Address') }}
                            </button>

                            <a href="{{ route('customer.profile.addresses.index') }}" class="text-gray-600 hover:text-gray-900">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
