<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-neutral-900">Edit Product</h2>
    </x-slot>

    <div class="py-12 bg-neutral-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-md rounded-lg">
                <form action="{{ route('vendor.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <x-ui.input-label for="name" value="Product Name" />
                        <x-ui.input type="text" name="name" id="name" required
                            :value="old('name', $product->name)"
                            class="mt-1 block w-full" />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-ui.input-label for="description" value="Description" />
                        <x-ui.textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full">{{ old('description', $product->description) }}</x-ui.textarea>
                    </div>

                    <!-- Price & Stock -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-ui.input-label for="price" value="Price ($)" />
                            <x-ui.input type="number" name="price" id="price" step="0.01" required
                                :value="old('price', $product->price)"
                                class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-ui.input-label for="stock" value="Stock" />
                            <x-ui.input type="number" name="stock" id="stock" required
                                :value="old('stock', $product->stock)"
                                class="mt-1 block w-full" />
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <x-ui.input-label for="category_id" value="Category" />
                        @php
                            $categoryOptions = $categories->map(fn($cat) => ['value' => $cat->id, 'label' => $cat->name])->toArray();
                        @endphp
                        <x-ui.select-dropdown id="category_id" name="category_id" :options="$categoryOptions" :selected="old('category_id', $product->category_id)" class="mt-1 block w-full" required />
                    </div>

                    <!-- Image -->
                    <div>
                        <x-ui.input-label for="image" value="Update Image" />
                        <x-ui.input type="file" name="image" id="image"
                            class="mt-1 block w-full" />
                        @if($product->image)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Current Product Image" class="w-32 h-auto rounded shadow">
                            </div>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <x-ui.link variant="secondary" href="{{ route('vendor.dashboard') }}" class="mr-2">
                            Cancel
                        </x-ui.link>
                        <x-ui.button type="submit" variant="primary">
                            ✅ Update Product
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
