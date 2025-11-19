<x-app-layout>
    <x-slot name="header">
      <h2 class="text-2xl font-bold">Categories</h2>
    </x-slot>
  
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-semibold text-gray-800">Category List</h3>
          <a href="{{ route('admin.categories.create') }}"
             class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
            + New Category
          </a>
        </div>
        <x-table.index>
            <x-table.head>
              <x-table.row>
                <x-table.header>ID</x-table.header>
                <x-table.header>Name</x-table.header>
                <x-table.header class="text-right">Actions</x-table.header>
              </x-table.row>
            </x-table.head>
            <x-table.body>
              @forelse($categories as $category)
                <x-table.row>
                  <x-table.data>{{ $category->id }}</x-table.data>
                  <x-table.data>{{ $category->name }}</x-table.data>
                  <x-table.actions class="space-x-2">
                    <x-ui.link variant="primary" href="{{ route('admin.categories.edit', $category) }}">Edit</x-ui.link>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                      @csrf @method('DELETE')
                      <x-ui.button variant="text" color="danger" type="submit">Delete</x-ui.button>
                    </form>
                  </x-table.actions>
                </x-table.row>
              @empty
                <x-table.empty>
                  No categories found.
                </x-table.empty>
              @endforelse
            </x-table.body>
          </x-table.index>
      </div>
    </div>
  </x-app-layout>
