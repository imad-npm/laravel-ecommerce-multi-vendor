<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">Edit Product</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-md rounded-lg">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" id="name" required
                            value="{{ old('name', $product->name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary focus:border-primary sm:text-sm">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Price & Stock -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                            <input type="number" name="price" id="price" step="0.01" required
                                value="{{ old('price', $product->price) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" name="stock" id="stock" required
                                value="{{ old('stock', $product->stock) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                    </div>

                    <!-- Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Update Image</label>
                        <input type="file" name="image" id="image"
                            class="mt-1 block w-full text-sm text-gray-600 file:border file:border-gray-300 file:rounded file:px-3 file:py-1 file:bg-white hover:file:bg-gray-100 file:cursor-pointer">
                        @if($product->image)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Current Product Image" class="w-32 h-auto rounded shadow">
                            </div>
                        @endif
                    </div>
                    <input type="hidden" name="category_id" value={{$product->category_id}} />
                    <!-- Submit -->
                    <div class="flex justify-end">
                        <a href="{{ route('admin.products.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-300 text-sm">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-primary text-white px-6 py-2 rounded shadow hover:bg-primary transition text-sm">
                            ✅ Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
