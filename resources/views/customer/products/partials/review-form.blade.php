<form method="POST" action="{{ route('customer.products.review', $product) }}" class="space-y-4 mt-6">
    @csrf
    <div>
        <label for="rating" class="block text-sm font-medium text-gray-700">Your Rating</label>
        <select name="stars" id="rating" required class="mt-1 block w-24 border-gray-300 rounded-md p-2">
            <option value="">Select</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
            @endfor
        </select>
    </div>
    <div>
        <label for="comment" class="block text-sm font-medium text-gray-700">Your Review</label>
        <textarea name="comment" id="comment" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md p-2"></textarea>
    </div>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm shadow">
        Submit Review
    </button>
</form>
