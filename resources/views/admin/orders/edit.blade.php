<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Edit Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-10 bg-gray-50">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">

            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                @csrf
                @method('PATCH')

                <!-- User info (read only) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Customer</label>
                    <input type="text" value="{{ $order->customer->name ?? 'Unknown' }}" readonly
                           class="mt-1 block w-full border-gray-300 rounded-md p-2 bg-gray-100 text-gray-600">
                </div>

                <!-- Shipping Address (read only) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                    <div class="mt-1 block w-full border-gray-300 rounded-md p-2 bg-gray-100 text-gray-600">
                        {{ $order->shipping_address_line_1 }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                        {{ $order->shipping_country }}
                    </div>
                </div>

              

                <!-- Status -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" required
                            class="mt-1 block w-full border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach(['pending', 'paid', 'shipped', 'cancelled'] as $status)
                            <option value="{{ $status }}" {{ old('status', $order->status) === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                <!-- Total (readonly) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Total</label>
                    <input type="text" value="${{ number_format($order->total, 2) }}" readonly
                           class="mt-1 block w-full border-gray-300 rounded-md p-2 bg-gray-100 text-gray-600">
                </div>

                <button type="submit"
                        class="w-full bg-indigo-600 text-white py-3 rounded hover:bg-indigo-700 transition">
                    Update Order
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
