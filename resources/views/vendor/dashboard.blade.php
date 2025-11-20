<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vendor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (!Auth::user()->stripe_account_id)
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Connect your Stripe Account</p>
                            <p>To receive payouts, please connect your Stripe account.</p>
                            <x-ui.button :href="route('vendor.stripe.connect')" variant="primary" class="mt-2">
                                Connect Stripe
                            </x-ui.button>
                        </div>
                    @else
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Stripe Account Connected</p>
                            <p>Your Stripe account is successfully connected. You are ready to receive payouts.</p>
                            <form action="{{ route('vendor.stripe.disconnect') }}" method="POST" class="inline-block mt-2">
                                @csrf
                                <x-ui.button type="submit" variant="danger">
                                    Disconnect Stripe
                                </x-ui.button>
                            </form>
                        </div>
                    @endif

                    <h3 class="text-2xl font-bold mb-6">Store Analytics</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Total Sales -->
                        <div class="bg-primary text-white p-6 rounded-lg shadow-lg">
                            <h4 class="text-lg font-semibold">Total Sales</h4>
                            <p class="text-3xl font-bold mt-2">${{ number_format($totalSales, 2) }}</p>
                        </div>

                        <!-- Total Orders -->
                        <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
                            <h4 class="text-lg font-semibold">Total Orders</h4>
                            <p class="text-3xl font-bold mt-2">{{ $totalOrders }}</p>
                        </div>

                        <!-- Total Products -->
                        <div class="bg-purple-500 text-white p-6 rounded-lg shadow-lg">
                            <h4 class="text-lg font-semibold">Total Products</h4>
                            <p class="text-3xl font-bold mt-2">{{ $totalProducts }}</p>
                        </div>
                    </div>

                    <!-- Top Selling Products -->
                    <div class="mt-8">
                        <h4 class="text-xl font-bold mb-4">Your Top Selling Products</h4>
                        <x-table.index>
                                <x-table.head>
                                    <x-table.row>
                                        <x-table.header>Product</x-table.header>
                                        <x-table.header>Price</x-table.header>
                                        <x-table.header>Orders</x-table.header>
                                    </x-table.row>
                                </x-table.head>
                                <x-table.body>
                                    @forelse ($topProducts as $product)
                                    <x-table.row>
                                        <x-table.header scope="row">
                                            {{ $product->name }}
                                        </x-table.header>
                                        <x-table.data>
                                            ${{ number_format($product->price, 2) }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ $product->orders_count }}
                                        </x-table.data>
                                    </x-table.row>
                                    @empty
                                    <x-table.empty>
                                        No products found.
                                    </x-table.empty>
                                    @endforelse
                                </x-table.body>
                            </x-table.index>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>