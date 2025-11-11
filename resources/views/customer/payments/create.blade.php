<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pay for Order') }}
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

                    <div class="mt-6">
                        <h4 class="font-semibold mb-2">Enter Credit Card Details</h4>
                        <div id="card-element" class="p-3 border rounded-md"></div>
                        <div id="card-errors" role="alert" class="text-sm text-red-600 mt-2"></div>
                    </div>

                    <button id="submit-button" class="mt-4 w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">
                        Pay Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stripeKey = "{{ config('services.stripe.key') }}";
            if (stripeKey) {
                const stripe = Stripe(stripeKey);
                const elements = stripe.elements();
                const cardElement = elements.create('card');
                cardElement.mount('#card-element');

                const submitButton = document.getElementById('submit-button');
                const cardErrors = document.getElementById('card-errors');

                submitButton.addEventListener('click', async (e) => {
                    e.preventDefault();
                    submitButton.disabled = true;
                    cardErrors.textContent = '';

                    const { paymentIntent, error } = await stripe.confirmCardPayment(
                        "{{ $paymentIntent->client_secret }}", {
                            payment_method: {
                                card: cardElement,
                                billing_details: {
                                    name: "{{ auth()->user()->name }}",
                                    email: "{{ auth()->user()->email }}"
                                }
                            }
                        }
                    );

                    if (error) {
                        cardErrors.textContent = error.message;
                        submitButton.disabled = false;
                    } else {
                        // Payment submitted, redirect to order page
                        window.location.href = "{{ route('customer.orders.show', $order) }}";
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
