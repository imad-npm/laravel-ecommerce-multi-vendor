@php
    use App\Enums\OrderStatus;

@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                👋 Welcome back, {{ Auth::user()->name }}!
            </h2>
            <span class="text-sm text-gray-500">Last login: {{ Auth::user()->updated_at->format('d M Y, H:i') }}</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- 🚀 Promo Banner --}}
            <div class="rounded-xl bg-gradient-to-r from-primary to-purple-600 p-6 flex items-center justify-between shadow-lg">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-1">Big Summer Sale!</h3>
                    <p class="text-white text-sm">Up to 50% off on selected products. Limited time only.</p>
                </div>
                <a href="{{ route('products.index') }}" class="bg-white text-primary font-semibold px-6 py-2 rounded-lg shadow hover:bg-gray-100 transition">Shop Now</a>
            </div>

        
            {{-- 🏷️ Categories --}}
            <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-primary mb-4">Shop by Category</h3>
                <div class="flex flex-wrap gap-4">
                    @foreach($categories ?? [] as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="bg-primary text-primary px-4 py-2 rounded-lg font-medium hover:bg-primary">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>

            {{-- ⚡ Quick Actions --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <a href="{{ route('products.index') }}"
                   class="bg-white border border-primary p-6 rounded-xl shadow hover:shadow-lg transition group">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-primary group-hover:underline">🛒 Browse Products</h4>
                        <svg class="h-5 w-5 text-primary group-hover:translate-x-1 transition" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Discover items from all our vendors.</p>
                </a>

                <a href="{{ route('customer.orders.index') }}"
                   class="bg-white border border-success-200 p-6 rounded-xl shadow hover:shadow-lg transition group">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-success-700 group-hover:underline">📦 My Orders</h4>
                        <svg class="h-5 w-5 text-success-400 group-hover:translate-x-1 transition" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">View status, tracking, and history.</p>
                </a>

                <a href="{{ route('customer.profile.edit') }}"
                   class="bg-white border border-gray-300 p-6 rounded-xl shadow hover:shadow-lg transition group">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-primary group-hover:underline">👤 Edit Profile</h4>
                        <svg class="h-5 w-5 text-gray-400 group-hover:translate-x-1 transition" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Change name, email or password securely.</p>
                </a>
                <a href="{{ route('cart-items.index') }}"
                   class="bg-white border border-yellow-200 p-6 rounded-xl shadow hover:shadow-lg transition group">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-yellow-700 group-hover:underline">🛒 View Cart</h4>
                        <svg class="h-5 w-5 text-yellow-400 group-hover:translate-x-1 transition" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">See your shopping cart and checkout.</p>
                </a>
            </div>

            {{-- 🌟 Featured Products --}}
            <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-primary mb-4">Featured Products</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($featured as $product)
                        <div class="bg-gray-50 rounded-lg p-4 flex flex-col items-center shadow hover:shadow-md transition">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded mb-2">
                            @else
                                <div class="w-24 h-24 bg-gray-200 rounded mb-2 flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                            <div class="font-semibold text-gray-700 text-center">{{ $product->name }}</div>
                            <div class="font-bold mt-1 text-primary">${{ number_format($product->price, 2) }}</div>
                            <x-ui.link variant="primary" href="{{ route('products.show', $product) }}" class="mt-2 text-sm">View</x-ui.link>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 🤖 Personalized Recommendations --}}
            <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-primary mb-4">Recommended for You</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($recommended as $product)
                        <div class="bg-gray-50 rounded-lg p-4 flex flex-col items-center shadow hover:shadow-md transition">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded mb-2">
                            @else
                                <div class="w-24 h-24 bg-gray-200 rounded mb-2 flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                            <div class="font-semibold text-gray-700 text-center">{{ $product->name }}</div>
                            <div class="font-bold mt-1 text-primary">${{ number_format($product->price, 2) }}</div>
                            <x-ui.link variant="primary" href="{{ route('products.show', $product) }}" class="mt-2 text-sm">View</x-ui.link>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 🕘 Recent Orders --}}
            <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-primary mb-4">Recent Orders</h3>
                <div class="space-y-4">
                    @forelse($orders as $order)
                        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4 shadow-sm">
                            <div>
                                <div class="font-semibold text-gray-700">Order #{{ $order->id }}</div>
                                <div class="text-xs text-gray-500">Placed {{ $order->created_at->diffForHumans() }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    @foreach($order->items as $item)
                                        <span>{{ $item->product->name }} × {{ $item->quantity }}</span>@if(!$loop->last), @endif
                                    @endforeach
                                </div>
                            </div>
                            @php
                                $statusClass = match($order->status) {
                                    OrderStatus::PAID => 'text-success-600',
                                    OrderStatus::PENDING => 'text-yellow-600',
                                    default => 'text-gray-600',
                                };
                            @endphp
                            <div class="{{ $statusClass }} font-bold text-sm">{{ ucfirst($order->status->value) }}</div>
                            <x-ui.link variant="primary" href="{{ route('customer.orders.index') }}" class="text-sm">View</x-ui.link>
                        </div>
                    @empty
                        <div class="text-gray-500 text-sm">No recent orders.</div>
                    @endforelse
                </div>
            </div>

            {{-- 💬 Support --}}
            <div class="bg-white rounded-xl shadow p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-primary mb-1">Need Help?</h3>
                    <p class="text-gray-600 text-sm">Our support team is here for you 24/7.</p>
                </div>
                <a href="#" class="bg-primary text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-primary transition">Contact Support</a>
            </div>
        </div>
    </div>
</x-app-layout>
