<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Add New Product</h2>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-800">Product Details</h3>
                </div>

                <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <!-- Price -->
                        <div>
                            <x-input-label for="price" :value="__('Price (USD)')" />
                            <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Stock -->
                        <div>
                            <x-input-label for="stock" :value="__('Stock Quantity')" />
                            <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-1" />
                        </div>

                        <!-- Category -->
                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Select a Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Image -->
                    <div>
                        <x-input-label for="image" :value="__('Product Image')" />
                        <input id="image" name="image" type="file"
                            class="mt-1 block w-full text-sm text-gray-500
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-md file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-indigo-50 file:text-indigo-700
                                   hover:file:bg-indigo-100"
                        />
                        <x-input-error :messages="$errors->get('image')" class="mt-1" />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <x-primary-button>
                            {{ __('Save Product') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
