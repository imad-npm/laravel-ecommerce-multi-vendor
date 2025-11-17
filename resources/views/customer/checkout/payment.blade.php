@extends('layouts.checkout')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Payment</h2>
    <div class="grid grid-cols-2 gap-8">
        <div>
            <h3 class="text-lg font-medium mb-4">Order Summary</h3>
            <div class="bg-gray-50 p-4 rounded-md">
                @if(isset($order))
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center mb-2">
                            <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                            <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <div class="flex justify-between font-bold">
                            <span>Total</span>
                            <span>${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                @else
                    @foreach($cart->items as $item)
                        <div class="flex justify-between items-center mb-2">
                            <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                            <span>${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <div class="flex justify-between font-bold">
                            <span>Total</span>
                            <span>${{ number_format($cart->total, 2) }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div>
            <h3 class="text-lg font-medium mb-4">Payment Method</h3>
            <form action="{{ isset($order) ? route('customer.orders.payment.retry.process', $order) : route('customer.checkout.payment.process') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center">
                            <x-ui.radio name="payment_method" value="stripe" />
                            <span class="ml-2">Credit Card (Stripe)</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center">
                            <x-ui.radio name="payment_method" value="paypal" />
                            <span class="ml-2">PayPal</span>
                        </label>
                    </div>
                </div>
                <x-ui.input-error :messages="$errors->get('payment_method')" class="mt-2" />
                <div class="mt-8 flex justify-between">
                    <x-ui.link href="{{ isset($order) ? route('customer.orders.show', $order) : route('customer.checkout.shipping') }}" variant="default">Back</x-ui.link>
                    <x-ui.button type="submit" variant="primary">{{ isset($order) ? 'Retry Payment' : 'Place Order' }}</x-ui.button>
                </div>
            </form>
        </div>
    </div>
@endsection
