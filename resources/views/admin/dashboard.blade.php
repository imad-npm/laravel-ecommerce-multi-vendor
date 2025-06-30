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
                        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
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
                        <div class="overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Product</th>
                                        <th scope="col" class="px-6 py-3">Price</th>
                                        <th scope="col" class="px-6 py-3">Orders</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topProducts as $product)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $product->name }}
                                        </th>
                                        <td class="px-6 py-4">
                                            ${{ number_format($product->price, 2) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $product->orders_count }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                            No products found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>