<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Payouts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-neutral-200">
                    <div class="flex justify-end mb-4">
                        <form action="{{ route('admin.payouts.payAll') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-primary hover:bg-primary text-white font-bold py-2 px-4 rounded">
                                {{ __('Pay All Unpaid Earnings') }}
                            </button>
                        </form>
                    </div>
                    <x-table.index>
                        <x-table.head>
                            <x-table.row>
                                <x-table.header>
                                    {{ __('Vendor') }}
                                </x-table.header>
                                <x-table.header>
                                    {{ __('Amount') }}
                                </x-table.header>
                                <x-table.header>
                                    {{ __('Status') }}
                                </x-table.header>
                                <x-table.header>
                                    {{ __('Transaction ID') }}
                                </x-table.header>
                                <x-table.header>
                                    <span class="sr-only">Actions</span>
                                </x-table.header>
                            </x-table.row>
                        </x-table.head>
                        <x-table.body>
                            @foreach ($payouts as $payout)
                                <x-table.row>
                                    <x-table.data>
                                        {{ $payout->vendor->name }}
                                    </x-table.data>
                                    <x-table.data>
                                        {{ $payout->amount }}
                                    </x-table.data>
                                    <x-table.data>
                                        {{ $payout->status }}
                                    </x-table.data>
                                    <x-table.data>
                                        {{ $payout->transaction_id }}
                                    </x-table.data>
                                    <x-table.actions>
                                        <x-ui.link variant="primary" href="{{ route('admin.payouts.show', $payout) }}">{{ __('View') }}</x-ui.link>
                                    </x-table.actions>
                                </x-table.row>
                            @endforeach
                        </x-table.body>
                    </x-table.index>
                    <div class="mt-4">
                        {{ $payouts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
