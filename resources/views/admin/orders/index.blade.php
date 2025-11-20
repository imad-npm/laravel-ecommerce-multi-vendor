@php
    use App\Enums\OrderStatus;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-primary">Orders List</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-primary mb-6">Order List</h3>
                <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4 w-full max-w-2xl flex flex-wrap gap-2 items-center bg-neutral-50 p-3 rounded shadow-sm">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Order ID, customer name or email..." class="flex-1 min-w-0 px-4 py-2 border border-neutral-300 rounded-lg" />
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg"> Search</button>
                </form>
                <x-table.index>
                        <x-table.head>
                            <x-table.row>
                                <x-table.header>ID</x-table.header>
                                <x-table.header>Customer</x-table.header>
                                <x-table.header>Total ($)</x-table.header>
                                <x-table.header>Status</x-table.header>
                                <x-table.header>Payment</x-table.header>
                                <x-table.header>Created At</x-table.header>
                                <x-table.header class="text-right">Actions</x-table.header>
                            </x-table.row>
                        </x-table.head>
                        <x-table.body>
                            @foreach ($orders as $order)
                                <x-table.row>
                                    <x-table.data>{{ $order->id }}</x-table.data>
                                    <x-table.data>{{ $order->customer->name ?? 'N/A' }}</x-table.data>
                                    <x-table.data>${{ number_format($order->total, 2) }}</x-table.data>
                                    <x-table.data>
                                        @php
                                            $statusClass = match($order->status) {
                                                OrderStatus::PENDING => 'bg-yellow-100 text-yellow-800',
                                                OrderStatus::PAID => 'bg-success-100 text-success-800',
                                                OrderStatus::SHIPPED => 'bg-primary text-primary',
                                                OrderStatus::CANCELLED => 'bg-red-100 text-red-800',
                                                default => 'bg-neutral-100 text-primary'
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ ucfirst($order->status->value) }}
                                        </span>
                                    </x-table.data>
                                    <x-table.data>{{ strtoupper($order->payment_method) }}</x-table.data>
                                    <x-table.data>{{ $order->created_at->format('Y-m-d H:i') }}</x-table.data>
                                    <x-table.actions class="space-x-2">
                                        <x-ui.link variant="primary" href="{{ route('admin.orders.show', $order) }}">View</x-ui.link>
                                        <x-ui.link variant="primary" href="{{ route('admin.orders.edit', $order) }}">Edit</x-ui.link>
                                        <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure to cancel this order?');">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button variant="text" color="danger" type="submit">Cancel</x-ui.button>
                                        </form>
                                    </x-table.actions>
                                </x-table.row>
                            @endforeach
                        </x-table.body>
                    </x-table.index>
            </div>
        </div>
    </div>
</x-app-layout>
