<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-10 bg-gray-50">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div>
                    <div class="text-lg font-semibold text-gray-700 mb-1">Customer</div>
                    <div class="text-gray-900">{{ $order->customer->name }} <span class="text-gray-500 text-sm">({{ $order->customer->email }})</span></div>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium 
                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <div class="text-sm text-gray-500">Shipping Address</div>
                                        <div class="text-gray-800 font-medium">
                        {{ $order->shipping_address_line_1 }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                        {{ $order->shipping_country }}
                    </div>
                </div>
                
            </div>
            <div class="mb-8">
                <div class="text-sm text-gray-500">Order Total</div>
                <div class="text-2xl font-bold text-indigo-700">${{ number_format($order->total, 2) }}</div>
            </div>
            <div>
                <h3 class="font-semibold text-lg mb-3 text-gray-800">Ordered Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Product</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Quantity</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Unit Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $item->product->name }}</td>
                                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2">${{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-2 font-semibold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-8">
                <a href="{{ route('admin.orders.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded shadow transition">Back to Orders</a>
            </div>
        </div>
    </div>
</x-app-layout>
