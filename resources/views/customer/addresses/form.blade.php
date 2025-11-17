@csrf
<div class="space-y-4">
    <div>
        <x-ui.input-label for="address_line_1" value="Address Line 1" />
        <x-ui.input id="address_line_1" type="text" name="address_line_1" :value="old('address_line_1', $address->address_line_1 ?? '')" required class="mt-1 block w-full" />
        <x-ui.input-error :messages="$errors->get('address_line_1')" class="mt-2" />
    </div>

    <div>
        <x-ui.input-label for="city" value="City" />
        <x-ui.input id="city" type="text" name="city" :value="old('city', $address->city ?? '')" required class="mt-1 block w-full" />
        <x-ui.input-error :messages="$errors->get('city')" class="mt-2" />
    </div>

    <div>
        <x-ui.input-label for="postal_code" value="Postal Code" />
        <x-ui.input id="postal_code" type="text" name="postal_code" :value="old('postal_code', $address->postal_code ?? '')" required class="mt-1 block w-full" />
        <x-ui.input-error :messages="$errors->get('postal_code')" class="mt-2" />
    </div>
</div>

<div class="flex justify-end mt-6">
    <x-ui.button variant="secondary" :href="request()->query('redirect') === 'checkout' ? route('customer.orders.create') : route('customer.addresses.index')">
        Cancel
    </x-ui.button>
    <x-ui.button variant="primary" type="submit" class="ml-4">
        {{ $buttonText ?? 'Save' }}
    </x-ui.button>
</div>
