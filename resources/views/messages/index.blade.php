<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                                <div class="@if ($message->sender_id === Auth::id()) bg-indigo-500 text-white @else bg-gray-200 text-gray-800 @endif p-3 rounded-lg max-w-xs">
                                    <p class="text-sm">{{ $message->message }}</p>
                                    <span class="text-xs opacity-75">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('conversations.messages.store', $conversation) }}" method="POST" class="mt-6">
                        @csrf
                        <textarea name="message" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Type your message..."></textarea>
                        <button type="submit" class="mt-2 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
