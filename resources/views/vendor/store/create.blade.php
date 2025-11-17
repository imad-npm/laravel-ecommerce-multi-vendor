
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">Create Store</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('vendor.store.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <x-ui.input-label for="name" value="Store Name" />
                    <x-ui.input type="text" name="name" id="name" class="mt-1 block w-full" required />
                </div>

                <div class="mb-6">
                    <x-ui.input-label for="description" value="Description" />
                    <x-ui.textarea name="description" id="description" rows="4" class="mt-1 block w-full" />
                </div>

                <div class="mb-6">
                    <x-ui.input-label for="logo" value="Logo (optional)" />
                    <x-ui.input type="file" name="logo" id="logo" class="mt-1 block w-full" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-ui.button variant="primary" type="submit">
                        Create Store
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
