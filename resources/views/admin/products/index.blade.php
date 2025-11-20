<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-primary">
            Products
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-primary">Product List</h3>

                </div>

                @include('partials.product.filters', [
                    'categories' => $categories,
                    'route' => 'admin.products.index',
                ])

                @if ($products->isEmpty())
                    <p class="text-gray-500">You haven’t added any products yet.</p>
                @else
                    <x-table.index>
                        <x-table.head>
                            <x-table.row>
                                <x-table.header>Image</x-table.header>
                                <x-table.header>Name</x-table.header>
                                <x-table.header>Price</x-table.header>
                                <x-table.header>Stock</x-table.header>
                                <x-table.header>Category</x-table.header>
                                <x-table.header>Avg. Rating</x-table.header>
                                <x-table.header>Actions</x-table.header>
                            </x-table.row>
                        </x-table.head>
                        <x-table.body>
                            @foreach ($products as $product)
                                <x-table.row>
                                    <x-table.data>
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                        @else
                                            <span class="text-gray-400 italic">No Image</span>
                                        @endif
                                    </x-table.data>
                                    <x-table.data class="font-medium text-gray-900">
                                        {{ $product->name }}
                                    </x-table.data>
                                    <x-table.data>${{ number_format($product->price, 2) }}</x-table.data>
                                    <x-table.data>{{ $product->stock }}</x-table.data>
                                    <x-table.data>
                                        @if ($product->category)
                                            <span
                                                class="inline-block px-2 py-1 text-xs font-semibold text-primary bg-primary rounded-full">
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic text-sm">N/A</span>
                                        @endif
                                    </x-table.data>
                                    <x-table.data>
                                        @if ($product->reviews->count() > 0)
                                            {{ number_format($product->reviews->avg('stars'), 1) }} / 5
                                        @else
                                            N/A
                                        @endif
                                    </x-table.data>

                                    <x-table.actions>
                                        <div class="flex space-x-3">
                                            <x-ui.link variant="primary"
                                                href="{{ route('admin.products.show', $product) }}">View</x-ui.link>
                                            <x-ui.link variant="primary"
                                                href="{{ route('admin.products.edit', $product) }}">Edit</x-ui.link>
                                            <form action="{{ route('admin.products.destroy', $product) }}"
                                                method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </x-table.actions>
                                </x-table.row>
                            @endforeach
                        </x-table.body>
                    </x-table.index>
                @endif
                <div class="mt-6">
                    {{ $products->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
