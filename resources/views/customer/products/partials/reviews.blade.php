@if($reviews->isNotEmpty())
    <div class="space-y-6">
        @foreach($reviews as $review)
            @include('customer.products.partials.review', ['review' => $review])
        @endforeach
    </div>
@else
    <p class="text-neutral-500 text-sm">No reviews yet. Be the first to review this product!</p>
@endif
