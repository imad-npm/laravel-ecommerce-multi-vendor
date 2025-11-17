<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">Edit Store</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('vendor.store.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <x-ui.input-label for="name" value="Store Name" />
                    <x-ui.input type="text" name="name" id="name" :value="old('name', $store->name)" class="mt-1 block w-full" required />
                </div>

                <div class="mb-6">
                    <x-ui.input-label for="description" value="Description" />
                    <x-ui.textarea name="description" id="description" rows="4" class="mt-1 block w-full">{{ old('description', $store->description) }}</x-ui.textarea>
                </div>

                <div class="mb-6">
                    <x-ui.input-label for="logo" value="Logo (optional)" />
                    <x-ui.input type="file" name="logo" id="logo" class="mt-1 block w-full" />
                    @if($store->logo)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">Current Logo:</p>
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="Current Store Logo" class="w-24 h-24 object-cover rounded-md shadow-sm mt-2">
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-ui.button variant="primary" type="submit">
                        Update Store
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
