@props(['product'])

<div class="group relative bg-white border border-neutral-300
   rounded-2xl shadow-sm hover:shadow-md w-full
   transition duration-300 col-span-1 flex flex-col ">


<a href="{{ route('products.show', $product) }}"
   >
    {{-- Image --}}
    <div class="  mx-auto overflow-hidden flex-shrink-0">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-in-out">
        @else
            <div class="h-full w-full bg-neutral-100 flex items-center justify-center text-neutral-400 text-sm">
                No Image Available
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="flex flex-col justify-between p-3 flex-grow overflow-hidden">
        <div class="space-y-1 overflow-hidden">
            <h3 class=" font-semibold text-neutral-900 truncate group-hover:underline">
                {{ $product->name }}
            </h3>

            @if($product->store)
                <div class="text-[9px] text-primary font-medium whitespace-nowrap truncate flex items-center">
                    <x-heroicon-o-building-storefront class="w-4 h-4 mr-1" />
                    <x-ui.link variant="primary" href="{{ route('stores.show', $product->store) }}">
                        <p class="text-sm font-medium text-neutral-900">{{ $product->store->name }}</p>
                    </x-ui.link>
                </div>
            @endif

            @if($product->category)
                <div class="text-[11px] text-primary whitespace-nowrap truncate flex items-center">
                    <x-heroicon-o-tag class="w-4 h-4 mr-1" />
                    {{ $product->category->name }}
                </div>
        
                
            @else
         noc        
            @endif

            {{-- Rating --}}
            <div class="flex items-center gap-1 text-yellow-400 text-[11px] overflow-hidden">
                @php
                    $rating = round($product->rating ?? 0, 1);
                    $fullStars = floor($rating);
                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                @endphp

                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $fullStars)
                        <x-heroicon-s-star class="w-3 h-3" />
                    @elseif($i === $fullStars + 1 && $hasHalfStar)
                        <x-icon.star-half class="w-3 h-3" />
                    @else
                        <x-heroicon-o-star class="w-3 h-3 text-neutral-300" />
                    @endif
                @endfor

                <span class="text-[10px] text-neutral-500 ml-1 truncate">({{ number_format($rating, 1) }})</span>
            </div>

            <div class="text-[10px] text-neutral-400 truncate flex items-center">
                <x-heroicon-o-archive-box class="w-4 h-4 mr-1" />
                {{ $product->sold_count ?? 0 }} units sold
            </div>
        </div>

        {{-- Price & Stock --}}
        <div class="mt-2 flex items-center justify-between">
            <span class="text-primary text-sm font-bold flex items-center">
                ${{ number_format($product->price, 2) }} 
            </span>
            <span class="text-[10px] px-1.5 py-0.5 rounded-full flex items-center
                {{ $product->stock > 0 ? 'bg-success-50 text-success-700' : 'bg-red-100 text-red-700' }}">
                @if($product->stock > 0)
                    <x-heroicon-o-check-circle class="w-3 h-3 mr-1" />
                    In Stock
                @else
                    <x-heroicon-o-x-circle class="w-3 h-3 mr-1" />
                    Out
                @endif
            </span>
        </div>
    </div>
</a>
</div>

