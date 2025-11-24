<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-primary">Product Details</h2>
    </x-slot>

    <div class="py-10 bg-neutral-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            {{-- Détails produit --}}
            <div class="bg-white shadow-lg rounded-2xl grid md:grid-cols-2 overflow-hidden">

                {{-- Image --}}
                <div class="bg-neutral-100 flex items-center justify-center p-8 relative">
                    @if ($product->sold_count > 50)
                        <span
                            class="absolute top-4 left-4 bg-yellow-400 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                            Best Seller
                        </span>
                    @endif

                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                            class="object-contain max-h-96 w-full rounded-lg">
                    @else
                        <div class="text-neutral-400">No Image Available</div>
                    @endif
                </div>

                {{-- Infos --}}
                <div class="p-8 flex flex-col justify-between space-y-6">
                    <div>
                        <h1 class="text-3xl font-bold text-neutral-900 mb-2">{{ $product->name }}</h1>

                        {{-- Étoiles + vente --}}
                        <div class="flex items-center gap-4 text-sm text-neutral-500 mb-4">
                            @php
                                $rating = round($product->rating ?? 0, 1);
                            @endphp

                            @if ($rating > 0)
                                @php
                                    $full = floor($rating);
                                    $half = $rating - $full >= 0.5;
                                @endphp
                                <div class="flex gap-0.5 text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $full)
                                            <x-icon.star solid class="w-4 h-4" />
                                        @elseif($i === $full + 1 && $half)
                                            <x-icon.star-half class="w-4 h-4 text-yellow-400" />
                                        @else
                                            <x-icon.star class="w-4 h-4 text-neutral-300" />
                                        @endif
                                    @endfor
                                </div>
                                <span>({{ number_format($rating, 1) }})</span>
                            @else
                                <div class="text-neutral-500">No reviews yet</div>
                            @endif

                            <span class="ml-4">{{ $product->sold_count }} sold</span>
                        </div>

                        {{-- Description --}}
                        @if ($product->description)
                            <p class="text-neutral-700 text-sm">{{ $product->description }}</p>
                        @endif

                        {{-- Prix & Stock --}}
                        <div class="mt-6 grid grid-cols-2 gap-6 text-sm">
                            <div>
                                <span class="block text-neutral-500 mb-1">Price</span>
                                <span
                                    class="text-2xl font-bold text-primary">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <div>
                                <span class="block text-neutral-500 mb-1">Stock</span>
                                <span
                                    class="text-lg font-medium {{ $product->stock > 0 ? 'text-success-600' : 'text-red-600' }}">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Boutons --}}
                    <div class="flex gap-4 mt-6">
                        <form method="POST"
                            action="{{ auth()->check() ? route('customer.cart-items.store') : route('cart-items.store') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <x-ui.button type="submit" variant="primary">
                                <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2" />
                                Add to Cart
                            </x-ui.button>
                        </form>
                        @auth
                            @if (Auth::id() !== $product->store->user->id)
                                @if ($conversation)
                                    <x-ui.button :href="route('conversations.messages.index', ['conversation' => $conversation->id])"
                                         variant="secondary" class="flex items-center">
                                        <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 mr-2" />
                                        Message Vendor
                                    </x-ui.button>
                                @else
                                    <form action="{{ route('conversations.store') }}" method="POST">
                                        @csrf
                                        <x-ui.input type="hidden" name="user_id" :value="$product->store->user->id" />
                                        <x-ui.input type="hidden" name="product_id" :value="$product->id" />
                                        <x-ui.button 
                                         variant="secondary" class="flex items-center">
                                            <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 mr-2" />
                                            Message Vendor
                                        </x-ui.button>
                                    </form>
                                @endif
                            @endif
                        @endauth
                        <x-ui.button :href="route('products.index')" variant="outline">
                            <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                            Back to Products
                        </x-ui.button>
                    </div>
                </div>
            </div>

            {{-- Avis clients --}}
            <div class="bg-white rounded-2xl shadow p-6 space-y-8">
                <h2 class="text-xl font-semibold text-primary">Customer Reviews</h2>

                @include('customer.products.partials.reviews', ['reviews' => $reviews])

                @auth
                    @if (auth()->user()->hasPurchased($product) && !$reviews->contains('user_id', auth()->id()))
                        @include('customer.products.partials.review-form', ['product' => $product])
                    @endif
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
