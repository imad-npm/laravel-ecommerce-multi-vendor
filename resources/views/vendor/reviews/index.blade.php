<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-primary">Product Reviews</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($reviews->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">No reviews yet for your products.</p>
                            <p class="text-gray-400 mt-2">Encourage your customers to leave feedback!</p>
                        </div>
                    @else
                        <x-table.index>
                                <x-table.head>
                                    <x-table.row>
                                        <x-table.header>Product</x-table.header>
                                        <x-table.header>Customer</x-table.header>
                                        <x-table.header>Rating</x-table.header>
                                        <x-table.header>Comment</x-table.header>
                                        <x-table.header>Date</x-table.header>
                                    </x-table.row>
                                </x-table.head>
                                <x-table.body>
                                    @foreach ($reviews as $review)
                                        <x-table.row>
                                            <x-table.data>{{ $review->product->name }}</x-table.data>
                                            <x-table.data>{{ $review->user->name }}</x-table.data>
                                            <x-table.data>{{ $review->stars }} / 5</x-table.data>
                                            <x-table.data>{{ $review->comment }}</x-table.data>
                                            <x-table.data>{{ $review->created_at->format('d/m/Y') }}</x-table.data>
                                        </x-table.row>
                                    @endforeach
                                </x-table.body>
                            </x-table.index>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
