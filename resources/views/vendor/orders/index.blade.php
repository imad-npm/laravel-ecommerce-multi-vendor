<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">📦 Orders for Your Products</h2>
    </x-slot>

    <div class="py-10 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @forelse ($items as $item)
                <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-200 hover:shadow-md transition">
                    <div class="md:flex justify-between items-start gap-6">
                        <!-- Product Info -->
                        <div class="flex-1 space-y-2">
                            <div class="text-lg font-semibold text-gray-800">
                                🛒 {{ $item->product->name }}
                            </div>
                            <div class="text-gray-600 text-sm">
                                <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                                <p><strong>Price per item:</strong> ${{ number_format($item->price, 2) }}</p>
                                <p><strong>Total:</strong> <span class="font-medium text-indigo-600">${{ number_format($item->quantity * $item->price, 2) }}</span></p>
                            </div>
                        </div>

                        <!-- Order Info -->
                        <div class="text-sm text-gray-600 mt-4 md:mt-0 text-right min-w-[200px]">
                            <p><strong>Order ID:</strong> #{{ $item->order->id }}</p>
                            <p><strong>Status:</strong>
                                <span class="
                                    px-2 py-1 rounded-full text-xs font-semibold
                                    {{ match($item->order->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'shipped' => 'bg-blue-100 text-blue-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    } }}
                                ">
                                    {{ ucfirst($item->order->status) }}
                                </span>
                            </p>
                            <p><strong>Customer:</strong> {{ $item->order->customer->name }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $item->order->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white p-10 rounded-lg shadow-sm text-center text-gray-600 border">
                    <p class="text-lg font-semibold">😕 No orders found for your products.</p>
                    <p class="text-sm text-gray-500 mt-2">You're all caught up!</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
