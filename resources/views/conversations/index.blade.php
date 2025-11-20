<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Your Conversations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($conversations->isEmpty())
                        <p>You have no conversations yet.</p>
                    @else
                        <ul role="list" class="divide-y divide-gray-100">
                            @foreach ($conversations as $conversation)
                                @php
                                    $otherUser =
                                        $conversation->user_one_id === Auth::id()
                                            ? $conversation->userTwo
                                            : $conversation->userOne;
                                    $productName = $conversation->product
                                        ? ' about ' . $conversation->product->name
                                        : '';
                                    $lastMessage = $conversation->messages->last();
                                @endphp
                                <li class="flex justify-between gap-x-6 py-5">
                                    <div class="flex min-w-0 gap-x-4">
                                        <div class="min-w-0 flex-auto">
                                            <p class="text-sm font-semibold leading-6 text-gray-900">
                                                <x-ui.link variant="primary"
                                                    href="{{ route('conversations.messages.index', ['conversation' => $conversation->id]) }}">
                                                    {{ $otherUser->name }} {{ $productName }}
                                                </x-ui.link>
                                            </p>
                                            @if ($lastMessage)
                                                <p class="mt-1 truncate text-xs leading-5 text-gray-500">
                                                    {{ $lastMessage->message }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="hidden sm:flex sm:flex-col sm:items-end">
                                        @if ($lastMessage)
                                            <p class="text-sm leading-6 text-gray-900">
                                                {{ $lastMessage->created_at->diffForHumans() }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
