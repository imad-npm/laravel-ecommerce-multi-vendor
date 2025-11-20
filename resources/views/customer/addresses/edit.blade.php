<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Edit Shipping Address') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-neutral-200">
                    <form action="{{ route('customer.addresses.update', $address) }}" method="POST">
                        @method('PATCH')
                        @include('customer.addresses.form', ['address' => $address, 'buttonText' => 'Update Address'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
