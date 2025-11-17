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
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs">
              <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($categories as $category)
                <tr class="border-b hover:bg-gray-50">
                  <td class="px-4 py-3">{{ $category->id }}</td>
                  <td class="px-4 py-3">{{ $category->name }}</td>
                  <td class="px-4 py-3 text-right space-x-2">
                    <x-ui.link variant="primary" href="{{ route('admin.categories.edit', $category) }}">Edit</x-ui.link>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                      @csrf @method('DELETE')
                      <x-ui.button variant="text" color="danger" type="submit">Delete</x-ui.button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="px-4 py-4 text-center text-gray-500">No categories found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </x-app-layout>
