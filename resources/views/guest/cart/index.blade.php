<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
            🛒 Your Shopping Cart
        </h2>
    </x-slot>

    @php
        $items = $cart->items ?? collect();
    @endphp

    <div class="py-12 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($items->isEmpty())
                <div class="bg-white p-10 text-center rounded-2xl shadow-lg text-gray-500 text-lg">
                    <p>Your cart is empty.</p>
                    <a href="{{ route('products.index') }}" class="mt-4 inline-block px-6 py-3 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow hover:bg-indigo-700 transition">
                        Start Shopping
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Liste des articles --}}
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 space-y-6">
                        @foreach($items as $item)
                            <div class="flex flex-col md:flex-row items-center justify-between border-b border-gray-200 pb-6 gap-4">
                                <div class="flex items-center gap-5 w-full md:w-auto">
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                         alt="{{ $item->product->name }}"
                                         class="w-24 h-24 object-cover rounded-lg shadow-sm">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            ${{ number_format($item->product->price, 2) }} × {{ $item->quantity }}
                                            <span class="ml-2 text-gray-500">= ${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                        </p>
                                    </div>
                                </div>

                                {{-- Actions : update/remove --}}
                                <div class="flex flex-col sm:flex-row items-center gap-3">
                                    <form action="{{ route('cart-items.update', $item->product_id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                               class="w-20 border-gray-300 rounded-lg p-2 text-center shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <button class="text-indigo-600 hover:underline text-sm transition">Update</button>
                                    </form>

                                    <form action="{{ route('cart-items.destroy', $item->product_id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline text-sm transition">Remove</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Résumé --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 h-fit sticky top-20">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Order Summary</h3>

                        <div class="space-y-2 text-sm text-gray-700">
                            @foreach($items as $item)
                                <div class="flex justify-between">
                                    <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                                    <span>${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4 mt-4">
                            <div class="flex justify-between text-lg font-bold text-gray-800">
                                <span>Total</span>
                                <span>${{ number_format($cart->total, 2) }}</span>
                            </div>
                        </div>

                        @auth
                            <a href="{{ route('customer.orders.create') }}"
                               class="mt-6 block w-full text-center px-6 py-3 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow hover:bg-indigo-700 transition">
                                Proceed to Checkout
                            </a>
                        @else
                            <div class="mt-6 text-sm text-center text-gray-500">
                                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login</a> to checkout.
                            </div>
                        @endauth
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
