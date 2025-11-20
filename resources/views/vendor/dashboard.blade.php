<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Vendor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-neutral-200">
                    <h3 class="text-2xl font-bold mb-6">Store Analytics</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Total Sales -->
                        <div class="bg-primary text-white p-6 rounded-lg shadow-lg">
                            <h4 class="text-lg font-semibold">Total Sales</h4>
                            <p class="text-3xl font-bold mt-2">${{ number_format($totalSales, 2) }}</p>
                        </div>

                        <!-- Total Orders -->
                        <div class="bg-success-500 text-white p-6 rounded-lg shadow-lg">
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