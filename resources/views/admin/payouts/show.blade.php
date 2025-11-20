<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Payout Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <p class="block text-gray-700 text-sm font-bold mb-2">{{ __('Vendor') }}: {{ $payout->vendor->name }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="block text-gray-700 text-sm font-bold mb-2">{{ __('Amount') }}: {{ $payout->amount }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="block text-gray-700 text-sm font-bold mb-2">{{ __('Transaction ID') }}: {{ $payout->transaction_id }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="block text-gray-700 text-sm font-bold mb-2">{{ __('Status') }}: {{ $payout->status }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.payouts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ __('Back to list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
