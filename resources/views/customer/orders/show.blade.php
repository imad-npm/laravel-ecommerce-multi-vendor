<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Order #{{ $order->id }}</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-8 text-gray-700">
                    <div>
                        <p class="text-lg"><strong class="font-semibold">Status:</strong> <span class="ml-2 px-3 py-1 rounded-full text-sm font-medium {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($order->status) }}</span></p>
                        <p class="text-lg mt-2"><strong class="font-semibold">Total:</strong> <span class="text-green-600 text-xl font-bold">${{ number_format($order->total, 2) }}</span></p>
                    </div>
                    <div>
                        <p class="text-lg"><strong class="font-semibold">Address:</strong> {{ $order->shippingAddress->address_line_1 ?? 'N/A' }}</p>
                        <p class="text-lg mt-2"><strong class="font-semibold">City:</strong> {{ $order->shippingAddress->city ?? 'N/A' }}</p>
                        <p class="text-lg mt-2"><strong class="font-semibold">Postal Code:</strong> {{ $order->shippingAddress->postal_code ?? 'N/A' }}</p>
                        <p class="text-lg mt-2"><strong class="font-semibold">Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Items</h3>
                <div class="bg-gray-50 rounded-lg p-4 mb-8">
                    <ul class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <li class="flex justify-between items-center py-3">
                                <div class="flex-1">
                                    <p class="text-lg font-medium text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <span class="text-lg font-semibold text-gray-900">${{ number_format($item->price, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-8 flex items-center gap-4">
                    <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Back to Orders
                    </a>
                    @if($order->status === 'pending')
                        <a href="{{ route('customer.orders.payments.create', $order) }}" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Pay Now
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
