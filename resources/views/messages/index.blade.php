<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            Chat with {{ $otherUser->name }} @if($conversation->product) about {{ $conversation->product->name }} @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col space-y-4">
                        @foreach ($messages as $message)
                            <div class="flex @if ($message->sender_id === Auth::id()) justify-end @else justify-start @endif">
                                <div class="@if ($message->sender_id === Auth::id()) bg-primary text-white @else bg-gray-200 text-primary @endif p-3 rounded-lg max-w-xs">
                                    <p class="text-sm">{{ $message->message }}</p>
                                    <span class="text-xs opacity-75">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('conversations.messages.store', $conversation) }}" method="POST" class="mt-6">
                        @csrf
                        <x-ui.textarea name="message" rows="3" class="w-full" placeholder="Type your message..."></x-ui.textarea>
                        <x-ui.button variant="primary" type="submit" class="mt-2">
                            Send Message
                        </x-ui.button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
