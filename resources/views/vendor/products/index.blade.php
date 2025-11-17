<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            My Products
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Product List</h3>
                    <x-ui.button :href="route('vendor.products.create')" variant="primary" size="sm">
                        + Add Product
                    </x-ui.button>
                </div>

                @if($products->isEmpty())
                    <p class="text-gray-500">You haven’t added any products yet.</p>
                @else
                    <div class="">
                        <table class="w-full text-sm text-left text-gray-700">
                            <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">Image</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Category</th>
                                    <th class="px-4 py-3">Price</th>
                                    <th class="px-4 py-3">Stock</th>
                                    <th class="px-4 py-3">Avg Rating</th>
                                    <th class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                            @else
                                                <span class="text-gray-400 italic">No Image</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-900">
                                            {{ $product->name }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $product->category->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3">${{ number_format($product->price, 2) }}</td>
                                        <td class="px-4 py-3">{{ $product->stock }}</td>
                                        <td class="px-4 py-3">
                                            @if($product->reviews_avg_stars)
                                                <div class="flex items-center">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= round($product->reviews_avg_stars))
                                                            <x-icon.star class="w-4 h-4 text-yellow-400" />
                                                        @else
                                                            <x-icon.star-outline class="w-4 h-4 text-gray-300" />
                                                        @endif
                                                    @endfor
                                                    <span class="ml-1 text-gray-600 text-xs">({{ number_format($product->reviews_avg_stars, 1) }})</span>
                                                </div>
                                            @else
                                                <span class="text-gray-500 text-xs">No ratings</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
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
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
