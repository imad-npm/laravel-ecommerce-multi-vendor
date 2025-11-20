<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden md:flex">
                
                <!-- Image -->
                @if($product->image)
                    <div class="md:w-1/3 bg-gray-100 p-4 flex items-center justify-center">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                             class="rounded-lg w-full h-auto object-cover max-h-64">
                    </div>
                @endif

                <!-- Details -->
                <div class="md:w-2/3 p-6 space-y-4">
                    <h3 class="text-2xl font-semibold text-primary">{{ $product->name }}</h3>

                    @if($product->description)
                        <p class="text-gray-700">{{ $product->description }}</p>
                    @endif

                    <div class="flex items-center space-x-6 mt-4">
                        <div>
                            <span class="block text-sm text-gray-500">Price</span>
                            <span class="text-xl font-bold text-primary">${{ number_format($product->price, 2) }}</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-500">Stock</span>
                            <span class="text-xl font-medium text-primary">{{ $product->stock }}</span>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-3">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-semibold rounded hover:bg-yellow-600">
                            ✏️ Edit
                        </a>
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-200 text-primary text-sm font-medium rounded hover:bg-gray-300">
                            ← Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
