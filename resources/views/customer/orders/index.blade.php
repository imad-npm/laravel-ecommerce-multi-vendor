@php
use App\Enums\OrderStatus;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-primary">🧾 My Orders</h2>
    </x-slot>

    <div class="py-10 bg-neutral-100">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- No orders --}}
            @if ($orders->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow text-center text-neutral-600">
                    <p class="text-lg font-semibold">You haven't placed any orders yet.</p>
                    <a href="{{ route('products.index') }}"
                        class="mt-4 inline-block px-5 py-2 bg-primary text-white text-sm rounded shadow hover:bg-primary transition">
                        Browse Products
                    </a>
                </div>

            {{-- Orders table --}}
            @else
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <h3 class="text-xl font-semibold text-primary mb-6">🕘 Order History</h3>
                    <x-table.index>
                            <x-table.head>
                                <x-table.row>
                                    <x-table.header>Order</x-table.header>
                                    <x-table.header>Date</x-table.header>
                                    <x-table.header>Items</x-table.header>
                                    <x-table.header>Total</x-table.header>
                                    <x-table.header>Status</x-table.header>
                                    <x-table.header class="text-right">Actions</x-table.header>
                                </x-table.row>
                            </x-table.head>
                            <x-table.body>
                                @foreach ($orders as $order)
                                    <x-table.row>
                                        <x-table.data class="font-bold text-primary">#{{ $order->id }}</x-table.data>
                                        <x-table.data class="text-neutral-500">{{ $order->created_at->format('M d, Y H:i') }}</x-table.data>
                                        <x-table.data>
                                            <div class="flex flex-col gap-2">
                                                @foreach ($order->items as $item)
                                                    <div class="flex items-center gap-2">
                                                        @if ($item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                alt="{{ $item->product->name }}"
                                                                class="w-8 h-8 object-cover rounded shadow-sm">
                                                        @else
                                                            <div class="w-8 h-8 bg-neutral-200 rounded flex items-center justify-center text-xs text-neutral-400">
                                                                No Img
                                                            </div>
                                                        @endif
                                                        <span class="text-primary font-medium">{{ $item->product->name }}</span>
                                                        <span class="text-neutral-500 text-xs">×{{ $item->quantity }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </x-table.data>
                                        <x-table.data class="font-semibold text-neutral-900">${{ number_format($order->total, 2) }}</x-table.data>
                                        <x-table.data>
                                            @php
                                                $statusClass = match ($order->status) {
                                                    OrderStatus::PENDING => 'bg-yellow-100 text-yellow-700',
                                                    OrderStatus::PAID => 'bg-success-100 text-success-700',
                                                    OrderStatus::SHIPPED => 'bg-primary text-primary',
                                                    OrderStatus::CANCELLED => 'bg-red-100 text-red-700',
                                                    default => 'bg-neutral-100 text-neutral-700',
                                                };
                                            @endphp
                                            <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $statusClass }}">
                                                {{ ucfirst($order->status->value) }}
                                            </span>

                                            @if ($order->status === OrderStatus::PENDING)
                                                @php
                                                    $expiresAt = $order->created_at->addDay();
                                                    $now = now();
                                                    $diffSeconds = $now->lt($expiresAt) ? $now->diffInSeconds($expiresAt) : 0;
                                                @endphp
                                                @if ($diffSeconds > 0)
                                                    <div x-data="{ remaining: {{ $diffSeconds }} }"
                                                         x-init="setInterval(() => { if (remaining > 0) remaining-- }, 1000)"
                                                         class="mt-1 text-xs text-red-600 font-semibold flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>
                                                            <span x-text="Math.floor(remaining / 3600)"></span>h
                                                            <span x-text="Math.floor((remaining % 3600) / 60)"></span>m remaining to pay
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="mt-1 text-xs text-red-600 font-semibold flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>Time's up</span>
                                                    </div>
                                                @endif
                                            @endif
                                        </x-table.data>
                                        <x-table.actions class="flex gap-2">
                                            <a href="{{ route('customer.orders.show', $order->id) }}" class="inline-block px-4 py-2 bg-primary text-white text-xs rounded shadow hover:bg-primary transition">Show</a>

                                            @if ($order->status === OrderStatus::PENDING)
                                                <a href="{{ route('customer.orders.checkout', $order->id) }}" class="inline-block px-4 py-2 bg-primary text-white text-xs rounded shadow hover:bg-primary transition">Pay</a>
                                                <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" class="inline-block"
                                                      onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                                    @csrf @method('PATCH')
                                                    <x-ui.button variant="text" color="danger" type="submit">Cancel</x-ui.button>
                                                </form>
                                            @endif
                                        </x-table.actions>
                                    </x-table.row>
                                @endforeach
                            </x-table.body>
                        </x-table.index>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
