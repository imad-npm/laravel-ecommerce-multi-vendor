@php
    use App\Enums\OrderStatus;

       $statusClass = match($order->status) {
                            OrderStatus::PAID => 'bg-success-50 text-success-700',
                            OrderStatus::PENDING => 'bg-yellow-100 text-yellow-700',
                            default => 'bg-red-100 text-red-700'
                        };
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <h1 class="text-3xl font-extrabold text-neutral-900 mb-6">Order #{{ $order->id }}</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-8 text-neutral-700">
                    <div>
                        
                        <p class="text-lg"><strong class="font-semibold">Status:</strong> <span class="ml-2 px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">{{ ucfirst($order->status->value) }}</span></p>
                        <p class="text-lg mt-2"><strong class="font-semibold">Total:</strong> <span class=" text-xl font-bold">${{ number_format($order->total, 2) }}</span></p>
                    </div>
                    <div>
                        <p class="text-lg"><strong class="font-semibold">Address:</strong> {{ $order->shipping_address_line_1 ?? 'N/A' }}</p>
                        <p class="text-lg mt-2"><strong class="font-semibold">City:</strong> {{ $order->shipping_city ?? 'N/A' }}</p>
                        <p class="text-lg mt-2"><strong class="font-semibold">Postal Code:</strong> {{ $order->shipping_postal_code ?? 'N/A' }}</p>
                        <p class="text-lg mt-2"><strong class="font-semibold">Country:</strong> {{ $order->shipping_country ?? 'N/A' }}</p>
                        <p class="text-lg mt-2"><strong class="font-semibold">Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-primary mb-4 border-b pb-2">Items</h3>
                <div class="bg-neutral-50 rounded-lg p-4 mb-8">
                    <ul class="divide-y divide-neutral-200">
                        @foreach($order->items as $item)
                            <li class="flex justify-between items-center py-3">
                                <div class="flex-1">
                                    <p class="text-lg font-medium text-neutral-900">{{ $item->product->name }}</p>
                                    <p class="text-sm text-neutral-500">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <span class="text-lg font-semibold text-neutral-900">${{ number_format($item->price, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-8 flex items-center gap-4">
                    <x:ui.button href="{{ route('customer.orders.index') }}" variant="outline" >
                     Back to Orders

                    </x:ui.button>
                    @if($order->status === OrderStatus::PENDING)
                        <x:ui.button variant="primary" href="{{ route('customer.orders.checkout', $order) }}" 
>                            Pay Now
                        </x:ui.button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
