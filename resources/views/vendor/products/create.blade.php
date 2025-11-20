<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-primary">Add New Product</h2>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-xl font-semibold text-primary">Product Details</h3>
                </div>

                <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <x-ui.input-label for="name" :value="__('Name')" />
                            <x-ui.input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                            <x-ui.input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <!-- Price -->
                        <div>
                            <x-ui.input-label for="price" :value="__('Price (USD)')" />
                            <x-ui.input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" required />
                            <x-ui.input-error :messages="$errors->get('price')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <x-ui.input-label for="description" :value="__('Description')" />
                        <x-ui.textarea id="description" name="description" rows="4"
                            class="mt-1 block w-full"
                        >{{ old('description') }}</x-ui.textarea>
                        <x-ui.input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Stock -->
                        <div>
                            <x-ui.input-label for="stock" :value="__('Stock Quantity')" />
                            <x-ui.input id="stock" name="stock" type="number" class="mt-1 block w-full" required />
                            <x-ui.input-error :messages="$errors->get('stock')" class="mt-1" />
                        </div>

                        <!-- Category -->
                        <div>
                            <x-ui.input-label for="category_id" :value="__('Category')" />
                            @php
                                $categoryOptions = $categories->map(fn($cat) => ['value' => $cat->id, 'label' => $cat->name])->prepend(['value' => '', 'label' => 'Select a Category'])->toArray();
                            @endphp
                            <x-ui.select-dropdown id="category_id" name="category_id" :options="$categoryOptions" :selected="old('category_id')" class="mt-1 block w-full" required />
                            <x-ui.input-error :messages="$errors->get('category_id')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Image -->
                    <div>
                        <x-ui.input-label for="image" :value="__('Product Image')" />
                        <x-ui.input id="image" name="image" type="file"
                            class="mt-1 block w-full"
                        />
                        <x-ui.input-error :messages="$errors->get('image')" class="mt-1" />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <x-ui.button variant="primary" type="submit">
                            {{ __('Save Product') }}
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
