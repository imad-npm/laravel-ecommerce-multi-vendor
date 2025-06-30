{{-- resources/views/customer/orders/retry-payment.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Retry Payment for Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-10 bg-gray-50">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('customer.orders.payment.retry.process', $order) }}" id="retry-payment-form">
                @csrf

                <!-- Shipping Address (readonly or prefilled) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                    <input type="text" name="address" value="{{ $order->address }}" readonly
                        class="mt-1 block w-full border-gray-300 rounded-md p-2 bg-gray-100 text-gray-600">
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <input type="radio" id="payment_card" name="payment_method" value="card" checked class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <label for="payment_card" class="text-gray-700">Credit Card</label>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="radio" id="payment_paypal" name="payment_method" value="paypal" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <label for="payment_paypal" class="text-gray-700">PayPal</label>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="radio" id="payment_stripe" name="payment_method" value="stripe" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <label for="payment_stripe" class="text-gray-700">Stripe</label>
                        </div>
                    </div>
                </div>

                <!-- Conditional Fields -->
                <div id="payment-details" class="hidden space-y-4">
                    <div id="credit-card-details" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Card Number</label>
                        <input type="text" name="card_number" placeholder="1234 5678 9012 3456"
                               class="mt-1 block w-full border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                <input type="text" name="expiry_date" placeholder="MM/YY"
                                       class="mt-1 block w-full border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">CVV</label>
                                <input type="text" name="cvv" placeholder="123"
                                       class="mt-1 block w-full border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div id="paypal-details" class="hidden">
                        <p class="text-sm text-gray-600">You will be redirected to PayPal to complete your payment.</p>
                    </div>

                    <div id="stripe-details" class="hidden">
                        <p class="text-sm text-gray-600">You will be redirected to Stripe to complete your payment.</p>
                    </div>
                </div>

                <!-- Total and Submit -->
                <div class="flex justify-between items-center border-t pt-4 mt-6">
                    <span class="text-lg font-bold">Total:</span>
                    <span class="text-lg font-bold">${{ number_format($order->total, 2) }}</span>
                </div>

                <button type="submit"
                        class="mt-6 w-full bg-indigo-600 text-white py-3 rounded hover:bg-indigo-700 transition">
                    Retry Payment
                </button>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const radios = document.querySelectorAll('input[name="payment_method"]');
            const details = document.getElementById('payment-details');
            const card = document.getElementById('credit-card-details');
            const paypal = document.getElementById('paypal-details');
            const stripe = document.getElementById('stripe-details');

            radios.forEach(radio => {
                radio.addEventListener('change', () => {
                    details.classList.remove('hidden');
                    card.classList.add('hidden');
                    paypal.classList.add('hidden');
                    stripe.classList.add('hidden');

                    if (radio.value === 'card') card.classList.remove('hidden');
                    else if (radio.value === 'paypal') paypal.classList.remove('hidden');
                    else if (radio.value === 'stripe') stripe.classList.remove('hidden');
                });

                // Trigger on page load
                if (radio.checked) radio.dispatchEvent(new Event('change'));
            });
        });
    </script>
</x-app-layout>
