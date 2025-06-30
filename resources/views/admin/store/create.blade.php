<x-app-layout>
    <x-slot name="header">
      <h2 class="text-2xl font-bold">Create New Store</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded shadow">
        <form method="POST" action="{{ route('admin.stores.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="mb-4">
            <label class="block text-sm font-medium">Name</label>
            <input name="name" value="{{ old('name') }}" required class="mt-1 w-full border-gray-300 rounded p-2">
            @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="mt-1 w-full border-gray-300 rounded p-2">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Logo</label>
            <input type="file" name="logo" class="mt-1 w-full border-gray-300 rounded p-2">
            @error('logo')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
          </div>
          <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Create Store</button>
        </form>
      </div>
    </div>
</x-app-layout>
