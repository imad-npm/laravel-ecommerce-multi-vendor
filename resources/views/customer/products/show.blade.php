<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Product Details</h2>
    </x-slot>

    <div class="py-10 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            {{-- Détails produit --}}
            <div class="bg-white shadow-lg rounded-2xl grid md:grid-cols-2 overflow-hidden">

                {{-- Image --}}
                <div class="bg-gray-100 flex items-center justify-center p-8 relative">
                    @if($product->sold_count > 50)
                        <span class="absolute top-4 left-4 bg-yellow-400 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                            Best Seller
                        </span>
                    @endif

                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="object-contain max-h-96 w-full rounded-lg">
                    @else
                        <div class="text-gray-400">No Image Available</div>
                    @endif
                </div>

                {{-- Infos --}}
                <div class="p-8 flex flex-col justify-between space-y-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>

                        {{-- Étoiles + vente --}}
                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                            @php
                                $rating = round($product->rating ?? 0, 1);
                            @endphp

                            @if ($rating > 0)
                                @php
                                    $full = floor($rating);
                                    $half = ($rating - $full) >= 0.5;
                                @endphp
                                <div class="flex gap-0.5 text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $full)
                                            <x-icon.star solid class="w-4 h-4"/>
                                        @elseif($i === $full + 1 && $half)
                                            <x-icon.star-half class="w-4 h-4 text-yellow-400"/>
                                        @else
                                            <x-icon.star class="w-4 h-4 text-gray-300"/>
                                        @endif
                                    @endfor
                                </div>
                                <span>({{ number_format($rating, 1) }})</span>
                            @else
                                <div class="text-gray-500">No reviews yet</div>
                            @endif

                            <span class="ml-4">{{ $product->sold_count }} sold</span>
                        </div>

                        {{-- Description --}}
                        @if($product->description)
                            <p class="text-gray-700 text-sm">{{ $product->description }}</p>
                        @endif

                        {{-- Prix & Stock --}}
                        <div class="mt-6 grid grid-cols-2 gap-6 text-sm">
                            <div>
                                <span class="block text-gray-500 mb-1">Price</span>
                                <span class="text-2xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 mb-1">Stock</span>
                                <span class="text-lg font-medium {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Boutons --}}
                    <div class="flex gap-4 mt-6">
                        <form method="POST" action="{{ route('cart-items.store') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm shadow">
                                🛒 Add to Cart
                            </button>
                        </form>
                        @auth
                            @if(Auth::id() !== $product->store->user->id)
                                @if($conversation)
                                    <a href="{{ route('conversations.show', ['conversation' => $conversation->id]) }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm shadow flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H16.5m3.065-12.69a11.955 11.955 0 0 1 .965 3.53l-1.798-.91c-.302-.152-.543-.413-.697-.72L16.5 4.5l1.798-.91c.302-.152.543-.413.697-.72ZM5.73 3.22a11.955 11.955 0 0 1 3.53.965l-.91 1.798c-.152.302-.413.543-.72.697L4.5 7.5l-.91-1.798a2.25 2.25 0 0 0-.72-.697A11.955 11.955 0 0 1 3.22 3.22Zm12.69 0a11.955 11.955 0 0 1-3.53.965l.91 1.798c.152.302.413.543.72.697L19.5 7.5l.91-1.798a2.25 2.25 0 0 0 .72-.697A11.955 11.955 0 0 1 18.91 3.22ZM3.22 5.73a11.955 11.955 0 0 1 .965 3.53l1.798-.91c.302-.152.543-.413.697-.72L7.5 4.5l.91 1.798a2.25 2.25 0 0 0 .72.697A11.955 11.955 0 0 1 5.73 3.22Z" />
                                        </svg>
                                        Message Vendor
                                    </a>
                                @else
                                    <form action="{{ route('conversations.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="receiver_id" value="{{ $product->store->user->id }}">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="message" value="Hello, I'm interested in this product: {{ $product->name }}">
                                        <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm shadow flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H16.5m3.065-12.69a11.955 11.955 0 0 1 .965 3.53l-1.798-.91c-.302-.152-.543-.413-.697-.72L16.5 4.5l1.798-.91c.302-.152.543-.413.697-.72ZM5.73 3.22a11.955 11.955 0 0 1 3.53.965l-.91 1.798c-.152.302-.413.543-.72.697L4.5 7.5l-.91-1.798a2.25 2.25 0 0 0-.72-.697A11.955 11.955 0 0 1 3.22 3.22Zm12.69 0a11.955 11.955 0 0 1-3.53.965l.91 1.798c.152.302.413.543.72.697L19.5 7.5l.91-1.798a2.25 2.25 0 0 0 .72-.697A11.955 11.955 0 0 1 18.91 3.22ZM3.22 5.73a11.955 11.955 0 0 1 .965 3.53l1.798-.91c.302-.152.543-.413.697-.72L7.5 4.5l.91 1.798a2.25 2.25 0 0 0 .72.697A11.955 11.955 0 0 1 5.73 3.22Z" />
                                            </svg>
                                            Message Vendor
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @endauth
                        <a href="{{ route('products.index') }}"
                           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-lg text-sm shadow">
                            ← Back to Products
                        </a>
                    </div>
                </div>
            </div>

            {{-- Avis clients --}}
            <div class="bg-white rounded-2xl shadow p-6 space-y-8">
                <h2 class="text-xl font-semibold text-gray-800">Customer Reviews</h2>

                @include('customer.products.partials.reviews', ['reviews' => $reviews])

                @auth
                    @if(auth()->user()->hasPurchased($product) && !$reviews->contains('user_id', auth()->id()))
                        @include('customer.products.partials.review-form', ['product' => $product])
                    @endif
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
