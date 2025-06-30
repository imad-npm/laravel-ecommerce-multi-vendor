<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight">Store</h2>
                <p class="text-sm text-gray-500 mt-1">Your store's public profile and settings.</p>
            </div>
            <a href="{{ route('vendor.store.edit') }}"
               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gray-800 text-white font-medium hover:bg-gray-800 transition-all duration-300 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5h2M12 7v10m9 2a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h7l2 2h7a2 2 0 012 2v14z"/>
                </svg>
                Edit Store
            </a>
        </div>
    </x-slot>

    <div class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden">

                {{-- Header Section with Logo --}}
                <div class="flex flex-col md:flex-row items-center md:items-start gap-8 p-10">
                    {{-- Logo --}}
                    <div>
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}"
                                 alt="{{ $store->name }}"
                                 class="w-40 h-40 object-cover rounded-2xl shadow border border-gray-300">
                        @else
                            <div class="w-40 h-40 flex items-center justify-center bg-gray-100 rounded-2xl border border-gray-300 text-gray-400 text-sm italic">
                                No logo
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1">
                        <h3 class="text-3xl font-semibold text-gray-800">{{ $store->name }}</h3>
                        <p class="text-gray-600 text-base mt-2 leading-relaxed">
                            {{ $store->description ?? 'No description provided.' }}
                        </p>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Actions Footer --}}
                <div class="flex items-center justify-end px-10 py-6 bg-gray-50">
                    <form action="{{ route('vendor.store.destroy') }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete your store? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-red-600 text-white font-semibold hover:bg-red-700 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="M20 12H4"/>
                            </svg>
                            Delete Store
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
