<x-app-layout>
    <x-slot name="header">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">🛍️ Discover Our Products</h2>
            <p class="text-sm text-gray-500">Free shipping on orders over $50</p>
        </div>

        @include('partials.product.filters')
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($products->isEmpty())
                <div class="bg-white p-10 rounded-lg shadow text-center text-gray-600">
                    <p class="text-xl font-medium">😕 No products found</p>
                    <p class="text-sm mt-2 text-gray-400">Try different filters or explore other categories</p>
                </div>
            @else
                <div class="grid grid-cols-2 gap-3 md:gap-6 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
