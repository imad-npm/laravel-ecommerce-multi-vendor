<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">🧾 My Orders</h2>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if ($orders->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow text-center text-gray-600">
                    <p class="text-lg font-semibold">You haven't placed any orders yet.</p>
                    <a href="{{ route('products.index') }}"
                        class="mt-4 inline-block px-5 py-2 bg-indigo-600 text-white text-sm rounded shadow hover:bg-indigo-700 transition">
                        Browse Products
                    </a>
                </div>
            @else
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">🕘 Order History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0 z-10">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Order</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Items</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-4 whitespace-nowrap font-bold text-indigo-700">
                                            #{{ $order->id }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-gray-500">
                                            {{ $order->created_at->format('M d, Y H:i') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex flex-col gap-2">
                                                @foreach ($order->items as $item)
                                                    <div class="flex items-center gap-2">
                                                        @if ($item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                alt="{{ $item->product->name }}"
                                                                class="w-8 h-8 object-cover rounded shadow-sm">
                                                        @else
                                                            <div
                                                                class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-400">
                                                                No Img</div>
                                                        @endif
                                                        <span
                                                            class="text-gray-800 font-medium">{{ $item->product->name }}</span>
                                                        <span
                                                            class="text-gray-500 text-xs">×{{ $item->quantity }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap font-semibold text-gray-900">
                                            ${{ number_format($order->total, 2) }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 text-xs rounded-full font-semibold
                                                {{ match ($order->status) {
                                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                                    'paid' => 'bg-green-100 text-green-700',
                                                    'shipped' => 'bg-blue-100 text-blue-700',
                                                    'cancelled' => 'bg-red-100 text-red-700',
                                                    default => 'bg-gray-100 text-gray-700',
                                                } }}
                                            ">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                            @if ($order->status === 'pending')
                                                @php
                                                    $expiresAt = $order->created_at->addDay();
                                                    $now = now();
                                                    $diffSeconds = $now->lt($expiresAt)
                                                        ? $now->diffInSeconds($expiresAt)
                                                        : 0;
                                                @endphp
                                                @if ($diffSeconds > 0)
                                                   <div 
    x-data="{ remaining: {{ $diffSeconds }} }" 
    x-init="setInterval(() => { if (remaining > 0) remaining-- }, 1000)"
    class="mt-1 text-xs text-red-600 font-semibold flex items-center gap-1"
>
    <svg class="w-4 h-4 text-red-500" fill="none"
         stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span>
        <span x-text="Math.floor(remaining / 3600)"></span>h
        <span x-text="Math.floor((remaining % 3600) / 60)"></span>m remaining to pay
    </span>
</div>

                                                @else
                                                    <div
                                                        class="mt-1 text-xs text-red-600 font-semibold flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-red-500" fill="none"
                                                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>Time's up</span>
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right">
                                            <a href="{{ route('customer.orders.show', $order->id) }}"
                                                class="inline-block px-4 py-2 bg-indigo-600 text-white text-xs rounded shadow hover:bg-indigo-700 transition">Show</a>
                                            @if ($order->status === 'pending')
                                                <a href="{{ route('customer.orders.payment.retry', $order->id) }}"
                                                    class="inline-block px-4 py-2 bg-indigo-600 text-white text-xs rounded shadow hover:bg-indigo-700 transition ml-2">Pay</a>
                                                <form action="{{ route('customer.orders.cancel', $order->id) }}"
                                                    method="POST" class="inline-block ml-2"
                                                    onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="inline-block px-4 py-2 bg-red-100 text-red-700 text-xs rounded shadow hover:bg-red-200 transition">Cancel</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
