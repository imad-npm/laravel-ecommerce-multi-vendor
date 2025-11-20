<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-bold mb-6">Dashboard Analytics</h3>
                    
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

                        <!-- Total Customers -->
                        <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-lg">
                            <h4 class="text-lg font-semibold">Total Customers</h4>
                            <p class="text-3xl font-bold mt-2">{{ $totalCustomers }}</p>
                        </div>
                    </div>

                    <!-- Top Selling Products -->
                    <div class="mt-8">
                        <h4 class="text-xl font-bold mb-4">Top Selling Products</h4>
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