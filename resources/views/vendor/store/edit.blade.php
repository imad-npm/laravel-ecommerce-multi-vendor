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
                    <label for="name" class="block text-sm font-medium text-gray-700">Store Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $store->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $store->description) }}</textarea>
                </div>

                <div class="mb-6">
                    <label for="logo" class="block text-sm font-medium text-gray-700">Logo (optional)</label>
                    <input type="file" name="logo" id="logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @if($store->logo)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">Current Logo:</p>
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="Current Store Logo" class="w-24 h-24 object-cover rounded-md shadow-sm mt-2">
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Update Store
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
