<x-app-layout>
    <x-slot name="header">
      <h2 class="text-2xl font-bold">Store #{{ $store->id }}</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded shadow space-y-4">
        <p><strong>Name:</strong> {{ $store->name }}</p>
        <p><strong>Description:</strong> {{ $store->description }}</p>
        <p><strong>Logo:</strong>
          @if($store->logo)
            <img src="{{ asset('storage/' . $store->logo) }}" class="w-20 h-20 rounded shadow">
          @endif
        </p>
        <div class="mt-4 flex space-x-4">
          <a href="{{ route('admin.stores.edit', $store) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
          <a href="{{ route('admin.stores.index') }}" class="px-4 py-2 bg-neutral-300 text-primary rounded hover:bg-neutral-400">Back to list</a>
        </div>
      </div>
    </div>
</x-app-layout>
