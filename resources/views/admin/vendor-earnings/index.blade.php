<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Vendor Earnings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-neutral-200">
                    <x-table.index>
                        <x-table.head>
                            <x-table.row>
                                <x-table.header>
                                    {{ __('Vendor') }}
                                </x-table.header>
                                <x-table.header>
                                    {{ __('Order') }}
                                </x-table.header>
                                <x-table.header>
                                    {{ __('Net Earnings') }}
                                </x-table.header>
                                <x-table.header>
                                    {{ __('Paid') }}
                                </x-table.header>
                                <x-table.header>
                                    {{ __('Payout') }}
                                </x-table.header>
                                <x-table.header>
                                    <span class="sr-only">Edit</span>
                                </x-table.header>
                            </x-table.row>
                        </x-table.head>
                        <x-table.body>
                            @foreach ($vendorEarnings as $earning)
                                <x-table.row>
                                    <x-table.data>
                                        {{ $earning->vendor->name }}
                                    </x-table.data>
                                    <x-table.data>
                                        {{ $earning->order->id }}
                                    </x-table.data>
                                    <x-table.data>
                                        {{ $earning->net_earnings }}
                                    </x-table.data>
                                    <x-table.data>
                                        {{ $earning->is_paid ? 'Yes' : 'No' }}
                                    </x-table.data>
                                    <x-table.data>
                                        @if ($earning->payout)
                                            <x-ui.link variant="primary" href="{{ route('admin.payouts.show', $earning->payout) }}">{{ $earning->payout->id }}</x-ui.link>
                                        @endif
                                    </x-table.data>
                                    <x-table.actions>
                                        <x-ui.link variant="primary" href="{{ route('admin.vendor-earnings.edit', $earning) }}">{{ __('Edit') }}</x-ui.link>
                                    </x-table.actions>
                                </x-table.row>
                            @endforeach
                        </x-table.body>
                    </x-table.index>
                    <div class="mt-4">
                        {{ $vendorEarnings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
