<div class="border-b pb-4 last:border-b-0 last:pb-0">
    <div class="flex items-center gap-3 mb-2">
        <span class="font-semibold text-primary">
            @auth
                @if($review->user_id === auth()->id())
                    Me
                @else
                    {{ $review->user->name }}
                @endif
            @else
                {{ $review->user->name }}
            @endauth
        </span>
        <span class="text-xs text-neutral-500">{{ $review->created_at->diffForHumans() }}</span>
    </div>
    <div class="flex items-center gap-1 mb-1">
        @for($i = 1; $i <= 5; $i++)
            @if($i <= $review->rating)
                <x-icon.star solid class="w-4 h-4 text-yellow-400" />
            @else
                <x-icon.star class="w-4 h-4 text-neutral-300" />
            @endif
        @endfor
    </div>
    <p class="text-neutral-700 text-sm">{{ $review->comment }}</p>
</div>
