<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-neutral-900">{{ $product->name }}</h2>
    </x-slot>

    <div class="py-12 bg-neutral-50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden md:flex">
                
                <!-- Image -->
                @if($product->image)
                    <div class="md:w-1/3 bg-neutral-100 p-4 flex items-center justify-center">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                             class="rounded-lg w-full h-auto object-cover max-h-64">
                    </div>
                @endif

                <!-- Details -->
                <div class="md:w-2/3 p-6 space-y-4">
                    <h3 class="text-2xl font-semibold text-primary">{{ $product->name }}</h3>

                    @if($product->description)
                        <p class="text-neutral-700">{{ $product->description }}</p>
                    @endif

                    <div class="flex items-center space-x-6 mt-4">
                        <div>
                            <span class="block text-sm text-neutral-500">Price</span>
                            <span class="text-xl font-bold text-primary">${{ number_format($product->price, 2) }}</span>
                        </div>
                        <div>
                            <span class="block text-sm text-neutral-500">Stock</span>
                            <span class="text-xl font-medium text-primary">{{ $product->stock }}</span>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-3">
                        <x-ui.button :href="route('admin.products.edit', $product)" variant="primary">
                            ✏️ Edit
                        </x-ui.button>
                        <x-ui.button :href="route('admin.dashboard')" variant="outline">
                            ← Back to Dashboard
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
