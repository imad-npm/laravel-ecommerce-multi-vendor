<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shipping Addresses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Your Saved Addresses') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __("Manage your shipping addresses.") }}
                    </p>

                    <div class="mt-6 space-y-6">
                        @forelse ($addresses as $address)
                            <div class="border p-4 rounded-lg flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">{{ $address->address_line_1 }}</p>
                                    <p>{{ $address->city }}, {{ $address->postal_code }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('customer.profile.addresses.edit', $address) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form method="POST" action="{{ route('customer.profile.addresses.destroy', $address) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this address?');">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600">No shipping addresses saved yet.</p>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('customer.profile.addresses.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Add New Address') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
