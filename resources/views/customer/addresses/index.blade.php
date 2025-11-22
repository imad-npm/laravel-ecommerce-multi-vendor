<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('My Shipping Addresses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-neutral-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-neutral-900">Manage Your Addresses</h3>
                        <x-ui.button :href="route('customer.addresses.create', ['redirect' => request()->query('redirect')])" variant="primary">{{ __('Add New Address') }}</x-ui.button>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-success-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="space-y-4">
                        @forelse ($shippingAddresses as $address)
                            <div class="p-4 border rounded-lg flex justify-between items-center">
                                <div>
                                    <p class="text-primary">{{ $address->address_line_1 }}</p>
                                    <p class="text-neutral-600">{{ $address->city }}, {{ $address->postal_code }}</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <x-ui.link variant="primary" href="{{ route('customer.addresses.edit', ['address' => $address, 'redirect' => request()->query('redirect')]) }}">Edit</x-ui.link>
                                    <form action="{{ route('customer.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button variant="text" color="danger" type="submit">Delete</x-ui.button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p>You have no saved shipping addresses.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
