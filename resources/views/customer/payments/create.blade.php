<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Payment Method') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Order #{{ $order->id }} - Total: ${{ number_format($order->total, 2) }}
                    </h3>

                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div x-data="{ selectedMethod: 'stripe' }">
                        <form action="{{ route('customer.orders.payments.store', $order) }}" method="POST" id="payment-form" novalidate>
                            @csrf

                            {{-- Payment method selection --}}
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer">
                                    <input type="radio" name="payment_method" value="stripe" class="form-radio h-5 w-5" x-model="selectedMethod" checked>
                                    <span class="ml-4 text-gray-800">Credit Card (Stripe)</span>
                                </label>

                                <label class="flex items-center p-4 border rounded-lg cursor-pointer">
                                    <input type="radio" name="payment_method" value="paypal" class="form-radio h-5 w-5" x-model="selectedMethod">
                                    <span class="ml-4 text-gray-800">PayPal</span>
                                </label>
                            </div>
                            @error('payment_method') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                            {{-- Stripe card form --}}
                            <div x-show="selectedMethod === 'stripe'" class="mt-6" x-cloak>
                                <h4 class="font-semibold mb-2">Enter Credit Card Details</h4>
                                <div id="card-element" class="p-3 border rounded-md"></div>
                                <div id="card-errors" role="alert" class="text-sm text-red-600 mt-2"></div>

                                <button id="stripe-submit-button" type="button" class="mt-4 w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">
                                    Pay with Card
                                </button>
                            </div>

                            {{-- PayPal button --}}
                            <div x-show="selectedMethod === 'paypal'" class="mt-6 text-center" x-cloak>
                                <h4 class="font-semibold mb-4">Pay with PayPal</h4>
                                <div id="paypal-button-container">
                                    <button type="button" class="px-4 py-2 bg-yellow-500 text-white rounded-md" onclick="mockPayPal()">Mock PayPal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stripe mock JS --}}
    <script>
        (function() {
            const form = document.getElementById('payment-form');
            const stripeButton = document.getElementById('stripe-submit-button');

            stripeButton.addEventListener('click', (e) => {
                e.preventDefault();

                // Remove previous hidden inputs
                const old = form.querySelector('input[name="payment_method_id"]');
                if(old) old.remove();

                // Mock Stripe payment_method_id
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'payment_method_id';
                input.value = 'pm_mock_12345'; // Mock ID
                form.appendChild(input);

                // Ensure payment_method field exists
                let pm = form.querySelector('input[name="payment_method"]');
                if(!pm) {
                    const hiddenPm = document.createElement('input');
                    hiddenPm.type = 'hidden';
                    hiddenPm.name = 'payment_method';
                    hiddenPm.value = 'stripe';
                    form.appendChild(hiddenPm);
                }

                // Submit form
                form.submit();
            });
        })();
    </script>

    {{-- PayPal mock JS --}}
    <script>
        function mockPayPal() {
            const form = document.getElementById('payment-form');

            // Remove previous hidden inputs
            const old = form.querySelector('input[name="paypal_order_id"]');
            if(old) old.remove();

            // Mock PayPal order ID
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'paypal_order_id';
            input.value = 'paypal_mock_12345';
            form.appendChild(input);

            // Ensure payment_method field exists
            let pm = form.querySelector('input[name="payment_method"]');
            if(!pm) {
                const hiddenPm = document.createElement('input');
                hiddenPm.type = 'hidden';
                hiddenPm.name = 'payment_method';
                hiddenPm.value = 'paypal';
                form.appendChild(hiddenPm);
            }

            // Submit form
            form.submit();
        }
    </script>
</x-app-layout>
