<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Stores</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-primary">Store List</h3>
                <x-ui.button :href="route('admin.stores.create')" variant="primary">+ New Store</x-ui.button>
            </div>
            <form method="GET" action="{{ route('admin.stores.index') }}"
                class="mb-4 w-full max-w-2xl flex flex-wrap gap-2 items-center bg-neutral-50 p-3 rounded shadow-sm">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                    class="flex-1 min-w-0 px-4 py-2 border border-neutral-300 rounded-lg" />
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg"> Search</button>
            </form>
            <x-table.index>
                <x-table.head>
                    <x-table.row>
                        <x-table.header>ID</x-table.header>
                        <x-table.header>Name</x-table.header>
                        <x-table.header>Description</x-table.header>
                        <x-table.header>Logo</x-table.header>
                        <x-table.header class="text-right">Actions</x-table.header>
                    </x-table.row>
                </x-table.head>
                <x-table.body>
                    @forelse($stores as $store)
                        <x-table.row>
                            <x-table.data>{{ $store->id }}</x-table.data>
                            <x-table.data>{{ $store->name }}</x-table.data>
                            <x-table.data class="max-w-[500px]">
                                {{ \Illuminate\Support\Str::limit($store->description, 50) }}
                            </x-table.data>
                            <x-table.data>
                                @if ($store->logo)
                                    <img src="{{ asset('storage/' . $store->logo) }}"
                                        class="w-12 h-12 rounded-full object-cover">
                                @endif
                            </x-table.data>
                            <x-table.actions class="space-x-2">
                                <x-ui.link variant="primary"
                                    href="{{ route('admin.stores.show', $store) }}">View</x-ui.link>
                                <x-ui.link variant="primary"
                                    href="{{ route('admin.stores.edit', $store) }}">Edit</x-ui.link>
                                <form action="{{ route('admin.stores.destroy', $store) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Delete this store?')">
                                    @csrf @method('DELETE')
                                    <x-ui.button variant="text" color="danger" type="submit">Delete</x-ui.button>
                                </form>
                            </x-table.actions>
                        </x-table.row>
                    @empty
                        <x-table.empty>No stores found.</x-table.empty>
                    @endforelse
                </x-table.body>
            </x-table.index>
            <div class="mt-4">
                {{ $stores->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
