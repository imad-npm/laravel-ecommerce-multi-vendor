<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-primary leading-tight">
            {{ __('Stripe Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-xl">
                <div class="p-6 sm:p-8 bg-white dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700">
                    
                    @if (!Auth::user()->stripe_account_id)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-5 rounded-md shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" role="alert">
                            <div>
                                <p class="font-semibold text-lg">Connect your Stripe Account</p>
                                <p class="text-sm mt-1">To receive payouts, please connect your Stripe account.</p>
                            </div>
                            <x-ui.button :href="route('vendor.stripe.connect')" variant="primary" class="mt-2 sm:mt-0">
                                Connect Stripe
                            </x-ui.button>
                        </div>
                    @else
                        <div class="bg-success-50 border-l-4 border-success-400 text-success-800 p-5 rounded-md shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" role="alert">
                            <div>
                                <p class="font-semibold text-lg">Stripe Account Connected</p>
                                <p class="text-sm mt-1">Your Stripe account is successfully connected.</p>
                                <p class="mt-1 text-neutral-700 dark:text-neutral-300 text-xs">
                                    Account ID: <strong> {{ Auth::user()->stripe_account_id }}</strong>
                                </p>
                            </div>
                            <form action="{{ route('vendor.stripe.disconnect') }}" method="POST" class="mt-2 sm:mt-0">
                                @csrf
                                <x-ui.button type="submit" variant="danger">
                                    Disconnect Stripe
                                </x-ui.button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
