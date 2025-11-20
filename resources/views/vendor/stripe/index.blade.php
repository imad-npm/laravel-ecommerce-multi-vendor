<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Stripe Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-neutral-200">
                    @if (!Auth::user()->stripe_account_id)
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Connect your Stripe Account</p>
                            <p>To receive payouts, please connect your Stripe account.</p>
                            <x-ui.button :href="route('vendor.stripe.connect')" variant="primary" class="mt-2">
                                Connect Stripe
                            </x-ui.button>
                        </div>
                    @else
                        <div class="bg-success-50 border-l-4 border-success-500 text-success-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Stripe Account Connected</p>
                            <p>Your Stripe account is successfully connected. You are ready to receive payouts.</p>
                            <form action="{{ route('vendor.stripe.disconnect') }}" method="POST" class="inline-block mt-2">
                                @csrf
                                <x-ui.button type="submit" variant="danger">
                                    Disconnect Stripe
                                </x-ui.button>
                            </form>
                        </div>

                        @if ($stripeAccount)
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold mb-2">Stripe Account Details:</h3>
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-neutral-500">Account ID:</dt>
                                        <dd class="mt-1 text-sm text-neutral-900">{{ $stripeAccount->id }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-neutral-500">Email:</dt>
                                        <dd class="mt-1 text-sm text-neutral-900">{{ $stripeAccount->email ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-neutral-500">Business Type:</dt>
                                        <dd class="mt-1 text-sm text-neutral-900">{{ ucfirst($stripeAccount->business_type ?? 'N/A') }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-neutral-500">Charges Enabled:</dt>
                                        <dd class="mt-1 text-sm text-neutral-900">
                                            @if ($stripeAccount->charges_enabled)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 text-success-800">Yes</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-neutral-500">Payouts Enabled:</dt>
                                        <dd class="mt-1 text-sm text-neutral-900">
                                            @if ($stripeAccount->payouts_enabled)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 text-success-800">Yes</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                            @endif
                                        </dd>
                                    </div>
                                    {{-- Add more details as needed --}}
                                </dl>
                            </div>
                        @else
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-6" role="alert">
                                <p class="font-bold">Error retrieving Stripe account details.</p>
                                <p>Please try again later or contact support.</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
