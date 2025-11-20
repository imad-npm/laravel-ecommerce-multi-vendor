<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            Conversation with {{ $otherUser->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <x-ui.link variant="primary" href="{{ route('conversations.messages.index', $conversation) }}">
    {{ $conversation->subject }}
</x-ui.link>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>