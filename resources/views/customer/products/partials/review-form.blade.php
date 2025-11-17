<form method="POST" action="{{ route('customer.products.review', $product) }}" class="space-y-4 mt-6">
    @csrf
    <div>
        <x-ui.input-label for="rating" value="Your Rating" />
        @php
            $ratings = [];
            for ($i = 5; $i >= 1; $i--) {
                $ratings[] = ['value' => $i, 'label' => $i . ' Star' . ($i > 1 ? 's' : '')];
            }
        @endphp
        <x-ui.select-dropdown id="rating" name="stars" :options="$ratings" required class="mt-1" />
    </div>
    <div>
        <x-ui.input-label for="comment" value="Your Review" />
        <x-ui.textarea name="comment" id="comment" rows="3" required class="mt-1 block w-full" />
    </div>
    <x-ui.button variant="primary" type="submit">
        Submit Review
    </x-ui.button>
</form>
