<div x-data="{ showFilters: false }" x-init="showFilters && document.body.classList.add('overflow-hidden')" x-effect="showFilters ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')">
    <form method="GET" action="{{ isset($route) ? route($route) : route('products.index') }}"
        class="w-full bg-white rounded-lg shadow-sm p-4 flex flex-col md:flex-row justify-center items-center gap-3 flex-wrap relative">
        {{-- Search --}}
        <div class="w-full md:w-1/2">
            <x-ui.input type="text" name="search" :value="request('search')" placeholder="Search products..."
                class="w-full text-base" />
        </div>
        {{-- Category --}}
        <div class="w-full md:w-44">
            <x-ui.select-dropdown name="category" :options="$categories
                ->map(fn($cat) => ['value' => $cat->id, 'label' => $cat->name])
                ->prepend(['value' => '', 'label' => 'All Categories'])" :selected="request('category')" class="!w-full md:!w-44" />
        </div>
        {{-- Button to open advanced filters modal --}}
        <div class="w-full sm:w-auto flex justify-end">
            <x-ui.button type="button" @click="showFilters = true" variant="outline" size="md" class="w-full sm:w-auto">
                <x-heroicon-o-adjustments-horizontal class="w-5 h-5 sm:mr-2" />
                <span class="hidden sm:inline">More Filters</span>
                <span class="sm:hidden">Filters</span>
            </x-ui.button>
        </div>
        {{-- Submit --}}
        <div class="w-full sm:w-auto flex justify-end">
            <x-ui.button type="submit" variant="primary" size="md" class="w-full sm:w-auto">
                <x-heroicon-o-magnifying-glass class="w-5 h-5 sm:mr-2" />
                <span class="hidden sm:inline">Search</span>
                <span class="sm:hidden">Go</span>
            </x-ui.button>
        </div>
        {{-- Advanced Filters Modal --}}
        <div x-show="showFilters" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-40 bg-black/40 flex items-center justify-center px-0 sm:px-4" style="display: none">
            <div @click.away="showFilters = false" x-transition
                class="bg-white w-full h-full sm:h-auto sm:w-full max-w-xl rounded-none sm:rounded-lg shadow-lg p-6 space-y-4 flex flex-col justify-center relative overflow-y-auto"
                tabindex="-1" x-ref="modal" @keydown.window.escape="showFilters = false" x-init="$watch('showFilters', v => { if(v) { setTimeout(() => $refs.modal.querySelector('input,select,button').focus(), 50) } })">
                <div class="flex justify-between items-center border-b pb-2 mb-4">
                    <h2 class="text-lg font-semibold text-primary">Advanced Filters</h2>
                    <x-ui.button @click="showFilters = false"  aria-label="Close" variant="text">&times;</x-ui.button>
                </div>
                {{-- Price Range --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    <x-ui.input type="number" name="min_price" min="0" step="0.01"
                        :value="request('min_price')" placeholder="Min $"
                        class="flex-1 text-base" />
                    <x-ui.input type="number" name="max_price" min="0" step="0.01"
                        :value="request('max_price')" placeholder="Max $"
                        class="flex-1 text-base" />
                </div>
                {{-- Rating --}}
                <div>
                    <x-ui.select-dropdown name="rating" class="w-full" :options="collect(range(5, 1))
                        ->map(fn($r) => ['value' => $r, 'label' => $r . '+ stars'])
                        ->prepend(['value' => '', 'label' => 'Min Rating'])" :selected="request('rating')" />
                </div>
                {{-- In Stock --}}
                <div class="flex items-center gap-2 text-sm text-neutral-700">
                    <x-ui.checkbox name="in_stock" value="1" :checked="request('in_stock')" id="in_stock" />
                    <label for="in_stock" class="cursor-pointer">Only show products in stock</label>
                </div>
                {{-- Sort --}}
                <div>
                    <x-ui.select-dropdown name="sort" class="w-full" :options="collect([
                        ['value' => '', 'label' => 'Sort: Newest'],
                        ['value' => 'price_asc', 'label' => 'Price: Low to High'],
                        ['value' => 'price_desc', 'label' => 'Price: High to Low'],
                        ['value' => 'rating', 'label' => 'Top Rated'],
                        ['value' => 'sold', 'label' => 'Best Selling'],
                    ])" :selected="request('sort')" />
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-2 pt-4 border-t">
                    <x-ui.button @click="showFilters = false" type="button" variant="outline" size="md" class="w-full sm:w-auto">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary" size="md" class="w-full sm:w-auto">
                        Apply Filters
                    </x-ui.button>
                </div>
            </div>
        </div>
    </form>
</div>
