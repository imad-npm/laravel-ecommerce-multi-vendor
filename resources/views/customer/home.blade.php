@php
    use App\Enums\OrderStatus;

@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-extrabold text-neutral-900 tracking-tight flex items-center">
                <x-heroicon-o-hand-thumb-up class="w-8 h-8 mr-2" />
                Welcome back, {{ Auth::user()->name }}!
            </h2>
            <span class="text-sm text-neutral-500">Last login: {{ Auth::user()->updated_at->format('d M Y, H:i') }}</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-neutral-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- 🚀 Promo Banner --}}
            <div class="rounded-xl bg-gradient-to-r from-primary to-purple-600 p-6 flex items-center justify-between shadow-lg">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-1">Big Summer Sale!</h3>
                    <p class="text-white text-sm">Up to 50% off on selected products. Limited time only.</p>
                </div>
                <a href="{{ route('products.index') }}" class="bg-white text-primary font-semibold px-6 py-2 rounded-lg shadow hover:bg-neutral-100 transition inline-flex items-center">
                    Shop Now
                    <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                </a>
            </div>

        
            {{-- 🏷️ Categories --}}
            <div class="bg-white rounded-xl shadow p-6 border border-neutral-300">
                <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                    <x-heroicon-o-tag class="w-6 h-6 mr-2" />
                    Shop by Category
                </h3>
                <div class="flex flex-wrap gap-4">
                    @foreach($categories ?? [] as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-primary">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>

            {{-- ⚡ Quick Actions --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <a href="{{ route('products.index') }}"
                   class="bg-white border border-neutral-300 p-6 rounded-xl shadow hover:shadow-lg transition group">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-primary group-hover:underline flex items-center">
                            <x-heroicon-o-shopping-bag class="w-6 h-6 mr-2" />
                            Browse Products
                        </h4>
                        <x-heroicon-o-arrow-right class="h-5 w-5 text-primary group-hover:translate-x-1 transition" />
                    </div>
                    <p class="text-sm text-neutral-500 mt-2">Discover items from all our vendors.</p>
                </a>

                <a href="{{ route('customer.orders.index') }}"
                   class="bg-white border border-neutral-300 p-6 rounded-xl shadow hover:shadow-lg transition group">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-semibold group-hover:underline flex items-center">
                            <x-heroicon-o-archive-box class="w-6 h-6 mr-2" />
                            My Orders
                        </h4>
                        <x-heroicon-o-arrow-right class="h-5 w-5 group-hover:translate-x-1 transition" />
                    </div>
                    <p class="text-sm text-neutral-500 mt-2">View status, tracking, and history.</p>
                </a>

                <a href="{{ route('customer.profile.edit') }}"
                   class="bg-white border border-neutral-300 p-6 rounded-xl shadow hover:shadow-lg transition group">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-primary group-hover:underline flex items-center">
                            <x-heroicon-o-user-circle class="w-6 h-6 mr-2" />
                            Edit Profile
                        </h4>
                        <x-heroicon-o-arrow-right class="h-5 w-5 text-neutral-400 group-hover:translate-x-1 transition" />
                    </div>
                    <p class="text-sm text-neutral-500 mt-2">Change name, email or password securely.</p>
                </a>
                <a href="{{ route('cart-items.index') }}"
                   class="bg-white border border-neutral-300 p-6 rounded-xl shadow hover:shadow-lg transition group">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-semibold group-hover:underline flex items-center">
                            <x-heroicon-o-shopping-cart class="w-6 h-6 mr-2" />
                            View Cart
                        </h4>
                        <x-heroicon-o-arrow-right class="h-5 w-5 group-hover:translate-x-1 transition" />
                    </div>
                    <p class="text-sm text-neutral-500 mt-2">See your shopping cart and checkout.</p>
                </a>
            </div>

            {{-- 🌟 Featured Products --}}
            <div class="bg-white rounded-xl shadow p-6 border border-neutral-300">
                <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                    <x-heroicon-o-sparkles class="w-6 h-6 mr-2" />
                    Featured Products
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($featured as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>

            {{-- 🤖 Personalized Recommendations --}}
            <div class="bg-white rounded-xl shadow p-6 border border-neutral-300">
                <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                    <x-heroicon-o-user class="w-6 h-6 mr-2" />
                    Recommended for You
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($recommended as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>

            {{-- 🕘 Recent Orders --}}
            <div class="bg-white rounded-xl shadow p-6 border border-neutral-300">
                <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                    <x-heroicon-o-receipt-refund class="w-6 h-6 mr-2" />
                    Recent Orders
                </h3>
                <div class="space-y-4">
                    @forelse($orders as $order)
                        <div class="flex items-center justify-between bg-neutral-50 rounded-lg p-4 shadow-sm">
                            <div>
                                <div class="font-semibold text-neutral-700">Order #{{ $order->id }}</div>
                                <div class="text-xs text-neutral-500">Placed {{ $order->created_at->diffForHumans() }}</div>
                                <div class="text-xs text-neutral-500 mt-1">
                                    @foreach($order->items as $item)
                                        <span>{{ $item->product->name }} × {{ $item->quantity }}</span>@if(!$loop->last), @endif
                                    @endforeach
                                </div>
                            </div>
                            @php
                                $statusClass = match($order->status) {
                                    OrderStatus::PAID => 'text-success-600',
                                    OrderStatus::PENDING => 'text-yellow-600',
                                    default => 'text-neutral-600',
                                };
                            @endphp
                            <div class="{{ $statusClass }} font-bold text-sm">{{ ucfirst($order->status->value) }}</div>
                            <x-ui.link variant="primary" href="{{ route('customer.orders.index') }}" class="text-sm">View</x-ui.link>
                        </div>
                    @empty
                        <div class="text-neutral-500 text-sm">No recent orders.</div>
                    @endforelse
                </div>
            </div>

            {{-- 💬 Support --}}
            <div class="bg-white rounded-xl shadow p-6 border border-neutral-300 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-primary mb-1 flex items-center">
                        <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 mr-2" />
                        Need Help?
                    </h3>
                    <p class="text-neutral-600 text-sm">Our support team is here for you 24/7.</p>
                </div>
                <a href="#" class="bg-primary text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-primary transition inline-flex items-center">
                    Contact Support
                    <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
