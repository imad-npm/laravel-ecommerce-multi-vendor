@csrf
<div class="space-y-4">
    <div>
        <label for="address_line_1" class="block text-sm font-medium text-gray-700">Address Line 1</label>
        <input type="text" name="address_line_1" id="address_line_1" value="{{ old('address_line_1', $address->address_line_1 ?? '') }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        @error('address_line_1')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
        <input type="text" name="city" id="city" value="{{ old('city', $address->city ?? '') }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        @error('city')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        @error('postal_code')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex justify-end mt-6">
    <a href="{{ request()->query('redirect') === 'checkout' ? route('customer.orders.create') : route('customer.addresses.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
        Cancel
    </a>
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
        {{ $buttonText ?? 'Save' }}
    </button>
</div>
