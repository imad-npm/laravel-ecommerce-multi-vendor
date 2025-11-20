<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Vendor Payouts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-neutral-200">
                    <h3 class="text-2xl font-bold mb-6">Payout History</h3>

                    <div class="bg-primary text-white p-6 rounded-lg shadow-lg mb-6">
                        <h4 class="text-lg font-semibold">Total Unpaid Earnings</h4>
                        <p class="text-3xl font-bold mt-2">${{ number_format($unpaidEarnings, 2) }}</p>
                    </div>

                    <x-table.index>
                        <x-table.head>
                            <x-table.row>
                                <x-table.header>ID</x-table.header>
                                <x-table.header>Amount</x-table.header>
                                <x-table.header>Status</x-table.header>
                                <x-table.header>Payment Method</x-table.header>
                                <x-table.header>Date</x-table.header>
                            </x-table.row>
                        </x-table.head>
                        <x-table.body>
                            @forelse ($payouts as $payout)
                                <x-table.row>
                                    <x-table.data>{{ $payout->id }}</x-table.data>
                                    <x-table.data>${{ number_format($payout->amount, 2) }}</x-table.data>
                                    <x-table.data>{{ $payout->status }}</x-table.data>
                                    <x-table.data>{{ $payout->payment_method }}</x-table.data>
                                    <x-table.data>{{ $payout->created_at->format('Y-m-d') }}</x-table.data>
                                </x-table.row>
                            @empty
                                <x-table.empty>
                                    No payout requests found.
                                </x-table.empty>
                            @endforelse
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
