@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Products</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-white rounded shadow p-4">
                <h2 class="text-lg font-semibold mb-2">{{ $product->name }}</h2>
                <p class="text-gray-600 mb-2">{{ $product->description }}</p>
                <p class="text-primary font-bold mb-2">${{ $product->price }}</p>
                <p class="text-sm text-gray-500 mb-2">
                    Store: <x-ui.link variant="primary" href="{{ route('stores.show', $product->store) }}">{{ $product->store->name }}</x-ui.link>
                </p>
                <x-ui.link variant="primary" href="{{ route('products.show', $product) }}">View Details</x-ui.link>
            </div>
        @endforeach
    </div>
</div>
@endsection
