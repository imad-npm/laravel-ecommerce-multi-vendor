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
          <x-ui.button :href="route('admin.stores.edit', $store)" variant="primary">
            <x-heroicon-o-pencil class="w-5 h-5 mr-2" />
            Edit
          </x-ui.button>
          <x-ui.button :href="route('admin.stores.index')" variant="outline">
            <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
            Back to list
          </x-ui.button>
        </div>
      </div>
    </div>
</x-app-layout>
