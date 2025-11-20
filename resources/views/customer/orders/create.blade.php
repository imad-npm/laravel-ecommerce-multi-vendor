<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Checkout</h2>
    </x-slot>

    <div class="py-10 bg-gray-50">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('customer.orders.store') }}" id="checkout-form">
                @csrf

                <!-- Shipping Address -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                    <input type="text" name="address" required class="mt-1 block w-full border-gray-300 rounded-md p-2 focus:ring-primary focus:border-primary">
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <div class="space-y-4">
                     
                        <!-- Credit Card -->
                        <div class="flex items-center gap-3">
                            <input type="radio" id="payment_card" name="payment_method" value="card" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary">
                            <label for="payment_card" class="text-gray-700">Credit Card</label>
                        </div>

                        <!-- PayPal -->
                        <div class="flex items-center gap-3">
                            <input type="radio" id="payment_paypal" name="payment_method" value="paypal" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary">
                            <label for="payment_paypal" class="text-gray-700">PayPal</label>
                        </div>

                        <!-- Stripe -->
                        <div class="flex items-center gap-3">
                            <input type="radio" id="payment_stripe" name="payment_method" value="stripe" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary">
                            <label for="payment_stripe" class="text-gray-700">Stripe</label>
                        </div>
                    </div>
                </div>

                <!-- Conditional Fields -->
                <div id="payment-details" class="hidden space-y-4">
                    <!-- Credit Card Details -->
                    <div id="credit-card-details" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Card Number</label>
                        <input type="text" name="card_number" placeholder="1234 5678 9012 3456" class="mt-1 block w-full border-gray-300 rounded-md p-2 focus:ring-primary focus:border-primary">

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                <input type="text" name="expiry_date" placeholder="MM/YY" class="mt-1 block w-full border-gray-300 rounded-md p-2 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">CVV</label>
                                <input type="text" name="cvv" placeholder="123" class="mt-1 block w-full border-gray-300 rounded-md p-2 focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                    </div>

                    <!-- PayPal Details -->
                    <div id="paypal-details" class="hidden">
                        <p class="text-sm text-gray-600">You will be redirected to PayPal to complete your payment.</p>
                    </div>

                    <!-- Stripe Details -->
                    <div id="stripe-details" class="hidden">
                        <p class="text-sm text-gray-600">You will be redirected to Stripe to complete your payment.</p>
                    </div>
                </div>

                <!-- Total and Submit Button -->
                <div class="flex justify-between items-center border-t pt-4 mt-6">
                    <span class="text-lg font-bold">Total:</span>
                    <span class="text-lg font-bold">
                        ${{ number_format($cart->items->sum(fn($i) => $i->product->price * $i->quantity), 2) }}
                    </span>
                </div>

                <button type="submit" class="mt-6 w-full bg-primary text-white py-3 rounded hover:bg-primary transition">
                    Confirm Order
                </button>
            </form>
        </div>
    </div>

    <!-- JavaScript for Conditional Fields -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
            const paymentDetailsContainer = document.getElementById('payment-details');
            const creditCardDetails = document.getElementById('credit-card-details');
            const paypalDetails = document.getElementById('paypal-details');
            const stripeDetails = document.getElementById('stripe-details');

            paymentMethodRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    // Hide all details first
                    paymentDetailsContainer.classList.add('hidden');
                    creditCardDetails.classList.add('hidden');
                    paypalDetails.classList.add('hidden');
                    stripeDetails.classList.add('hidden');

                    // Show relevant details based on selected payment method
                    if (radio.value === 'card') {
                        paymentDetailsContainer.classList.remove('hidden');
                        creditCardDetails.classList.remove('hidden');
                    } else if (radio.value === 'paypal') {
                        paymentDetailsContainer.classList.remove('hidden');
                        paypalDetails.classList.remove('hidden');
                    } else if (radio.value === 'stripe') {
                        paymentDetailsContainer.classList.remove('hidden');
                        stripeDetails.classList.remove('hidden');
                    }
                });
            });
        });
    </script>
</x-app-layout>