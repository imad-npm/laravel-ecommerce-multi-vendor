<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-6 mb-4">
            @if($store->logo)
                <img src="{{ asset('storage/' . $store->logo) }}" class="w-24 h-24 rounded shadow border">
            @endif
            <div>
                <h1 class="text-3xl font-bold mb-1 text-neutral-900">{{ $store->name }}</h1>
                <div class="flex items-center gap-3 text-neutral-600 text-sm mb-1">
                    <span>Vendor: <span class="font-semibold text-primary">{{ $store->user->name ?? 'N/A' }}</span></span>
                    <span class="ml-4 flex items-center">
                        <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.394c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                        <span class="font-semibold">{{ $avgRating }}</span> <span class="ml-1 text-xs text-neutral-400">/ 5</span>
                    </span>
                </div>
                <p class="text-neutral-700">{{ $store->description }}</p>
                @if ($conversation)
                    <a href="{{ route('chat.show', ['conversation' => $conversation->id]) }}"
                       class="mt-4 inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary active:bg-primary focus:outline-none focus:border-primary focus:ring ring-primary disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H16.5m3.065-12.69a11.955 11.955 0 0 1 .965 3.53l-1.798-.91c-.302-.152-.543-.413-.697-.72L16.5 4.5l1.798-.91c.302-.152-.543-.413-.697-.72ZM5.73 3.22a11.955 11.955 0 0 1 3.53.965l-.91 1.798c-.152.302-.413.543-.72.697L4.5 7.5l-.91-1.798a2.25 2.25 0 0 0-.72-.697A11.955 11.955 0 0 1 3.22 3.22Zm12.69 0a11.955 11.955 0 0 1-3.53.965l.91 1.798c.152.302.413.543-.72.697L19.5 7.5l.91-1.798a2.25 2.25 0 0 0 .72-.697A11.955 11.955 0 0 1 18.91 3.22ZM3.22 5.73a11.955 11.955 0 0 1 .965 3.53l1.798-.91c-.302-.152-.543-.413-.697-.72L7.5 4.5l.91 1.798a2.25 2.25 0 0 0 .72.697A11.955 11.955 0 0 1 5.73 3.22Z" />
                        </svg>
                        Message Vendor
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-xl font-semibold mt-8 mb-4">Products</h2>
       
              <div class="grid grid-cols-2 gap-3 md:gap-6 sm:grid-cols-3 lg:grid-cols-4">
            @forelse($store->products as $product)
                <x-product-card :product="$product" />
                    
                
            @empty
                <p>No products found for this store.</p>
            @endforelse
        </div>
    </div></div>
</x-app-layout>