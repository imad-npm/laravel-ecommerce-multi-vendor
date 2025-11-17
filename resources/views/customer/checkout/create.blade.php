<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('customer.orders.store') }}" method="POST" id="checkout-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Shipping and Payment Column -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <!-- Shipping Address Selection -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Address</h3>
                        
                        <div class="space-y-4" x-data="{ selectedAddress: '{{ $shippingAddresses->first()->id ?? '' }}' }">
                            @forelse ($shippingAddresses as $address)
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer">
                                    <x-ui.radio name="shipping_address_id" :value="$address->id" x-model="selectedAddress" />
                                    <div class="ml-4">
                                        <p class="text-gray-800">{{ $address->address_line_1 }}</p>
                                        <p class="text-gray-600">{{ $address->city }}, {{ $address->postal_code }}</p>
                                    </div>
                                </label>
                            @empty
                                <p>You have no saved shipping addresses.</p>
                            @endforelse

                            <!-- Link to add a new address -->
                            <div class="mt-4">
                                <a href="{{ route('customer.addresses.create', ['redirect' => 'checkout']) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    + Add a new address
                                </a>
                            </div>
                        </div>

                        <x-ui.input-error :messages="$errors->get('shipping_address_id')" class="mt-2" />
                        <x-ui.input-error :messages="$errors->get('new_address.*')" class="mt-2" />

                        <!-- Payment Method Selection -->
                        <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4">Payment Method</h3>
                        <div class="space-y-4">
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer">
                                <x-ui.radio name="payment_method" value="stripe" checked />
                                <span class="ml-4 text-gray-800">Credit Card (Stripe)</span>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer">
                                <x-ui.radio name="payment_method" value="paypal" />
                                <span class="ml-4 text-gray-800">PayPal</span>
                            </label>
                        </div>
                        <x-ui.input-error :messages="$errors->get('payment_method')" class="mt-2" />
                    </div>

                    <!-- Cart Summary Column -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                        <div class="space-y-4">
                            @foreach ($cart->items as $item)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                    </div>
                                    <p class="text-gray-800">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t mt-4 pt-4 flex justify-between items-center">
                            <p class="text-lg font-semibold">Total</p>
                            <p class="text-lg font-semibold">${{ number_format($cart->items->sum(fn($i) => $i->product->price * $i->quantity), 2) }}</p>
                        </div>
                        <div class="mt-6">
                            <x-ui.button type="submit" variant="primary" class="w-full justify-center">
                                Place Order and Proceed to Payment
                            </x-ui.button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</x-app-layout>
