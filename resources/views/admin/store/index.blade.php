<x-app-layout>
    <x-slot name="header">
      <h2 class="text-2xl font-bold">Stores</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-semibold text-gray-800">Store List</h3>
          <a href="{{ route('admin.stores.create') }}"
             class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
            + New Store
          </a>
        </div>
                        <form method="GET" action="{{ route('admin.stores.index') }}" class="mb-4 w-full max-w-2xl flex flex-wrap gap-2 items-center bg-gray-50 p-3 rounded shadow-sm">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." class="flex-1 min-w-0 px-4 py-2 border border-gray-300 rounded-lg" />
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg"> Search</button>
                </form>
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs">
              <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Description</th>
                <th class="px-4 py-3">Logo</th>
                <th class="px-4 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($stores as $store)
                <tr class="border-b hover:bg-gray-50">
                  <td class="px-4 py-3">{{ $store->id }}</td>
                  <td class="px-4 py-3">{{ $store->name }}</td>
                  <td class="px-4 py-3 max-w-[500px]">{{ $store->description }}</td>
                  <td class="px-4 py-3">
                    @if($store->logo)
                      <img src="{{ asset('storage/' . $store->logo) }}" class="w-12 h-12 rounded-full object-cover">
                    @endif
                  </td>
                  <td class="px-4 py-3 text-right space-x-2">
                    <x-ui.link variant="primary" href="{{ route('admin.stores.show', $store) }}">View</x-ui.link>
                    <x-ui.link variant="primary" href="{{ route('admin.stores.edit', $store) }}">Edit</x-ui.link>
                    <form action="{{ route('admin.stores.destroy', $store) }}" method="POST" class="inline" onsubmit="return confirm('Delete this store?')">
                      @csrf @method('DELETE')
                      <x-ui.button variant="text" color="danger" type="submit">Delete</x-ui.button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="px-4 py-3 text-center text-gray-500">No stores found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-4">
          {{ $stores->links() }}
        </div>
      </div>
    </div>
</x-app-layout>
