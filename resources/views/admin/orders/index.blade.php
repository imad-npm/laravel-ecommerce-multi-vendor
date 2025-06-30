<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Orders List</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Order List</h3>
                <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4 w-full max-w-2xl flex flex-wrap gap-2 items-center bg-gray-50 p-3 rounded shadow-sm">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Order ID, customer name or email..." class="flex-1 min-w-0 px-4 py-2 border border-gray-300 rounded-lg" />
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg"> Search</button>
                </form>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Total ($)</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Payment</th>
                                <th class="px-4 py-3">Created At</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $order->id }}</td>
                                    <td class="px-4 py-3">{{ $order->customer->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">${{ number_format($order->total, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'paid') bg-green-100 text-green-800
                                            @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ strtoupper($order->payment_method) }}</td>
                                    <td class="px-4 py-3">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline">View</a>
                                        <a href="{{ route('admin.orders.edit', $order) }}" class="text-yellow-600 hover:underline">Edit</a>
                                        <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure to cancel this order?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:underline">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
