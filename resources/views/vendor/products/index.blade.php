<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-primary">
            My Products
        </h2>
    </x-slot>

    <div class="py-10 bg-neutral-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-primary">Product List</h3>
                    <x-ui.button :href="route('vendor.products.create')" variant="primary" size="sm">
                        + Add Product
                    </x-ui.button>
                </div>

                @if($products->isEmpty())
                    <p class="text-neutral-500">You haven’t added any products yet.</p>
                @else
                    <x-table.index>
                            <x-table.head>
                                <x-table.row>
                                    <x-table.header>Image</x-table.header>
                                    <x-table.header>Name</x-table.header>
                                    <x-table.header>Category</x-table.header>
                                    <x-table.header>Price</x-table.header>
                                    <x-table.header>Stock</x-table.header>
                                    <x-table.header>Avg Rating</x-table.header>
                                    <x-table.header>Actions</x-table.header>
                                </x-table.row>
                            </x-table.head>
                            <x-table.body>
                                @foreach ($products as $product)
                                    <x-table.row>
                                        <x-table.data>
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                            @else
                                                <span class="text-neutral-400 italic">No Image</span>
                                            @endif
                                        </x-table.data>
                                        <x-table.data class="font-medium text-neutral-900">
                                            {{ $product->name }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ $product->category->name ?? 'N/A' }}
                                        </x-table.data>
                                        <x-table.data>${{ number_format($product->price, 2) }}</x-table.data>
                                        <x-table.data>{{ $product->stock }}</x-table.data>
                                        <x-table.data>
                                            @if($product->reviews_avg_stars)
                                                <div class="flex items-center">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= round($product->reviews_avg_stars))
                                                            <x-icon.star class="w-4 h-4 text-yellow-400" />
                                                        @else
                                                            <x-icon.star-outline class="w-4 h-4 text-neutral-300" />
                                                        @endif
                                                    @endfor
                                                    <span class="ml-1 text-neutral-600 text-xs">({{ number_format($product->reviews_avg_stars, 1) }})</span>
                                                </div>
                                            @else
                                                <span class="text-neutral-500 text-xs">No ratings</span>
                                            @endif
                                        </x-table.data>
                                        <x-table.actions>
                                            <div class="flex space-x-3">
                                                <x-ui.link variant="primary" href="{{ route('vendor.products.show', $product) }}">View</x-ui.link>
                                                <x-ui.link variant="primary" href="{{ route('vendor.products.edit', $product) }}">Edit</x-ui.link>
                                                <form action="{{ route('vendor.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-ui.button type="submit" variant="text" color="danger">
                                                        Delete
                                                    </x-ui.button>
                                                </form>
                                            </div>
                                        </x-table.actions>
                                    </x-table.row>
                                @endforeach
                            </x-table.body>
                        </x-table.index>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
