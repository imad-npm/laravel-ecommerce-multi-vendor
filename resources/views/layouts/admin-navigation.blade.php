@php
      use App\Models\Cart;
      use App\Enums\UserRole;
      use App\Services\Cart\CustomerCartService;
      use App\Services\Cart\GuestCartService;

      $user = auth()->user();
      $role = $user?->role->value ?? 'guest';

      function dashboard_active()
      {
          return match (auth()->user()?->role) {
              UserRole::ADMIN => request()->routeIs('admin.dashboard'),
              UserRole::VENDOR => request()->routeIs('vendor.dashboard'),
              UserRole::CUSTOMER => request()->routeIs('customer.home'),
              default => request()->routeIs(''),
          };
      }

      function profile_route()
      {
          return match (auth()->user()?->role) {
              UserRole::ADMIN => route('admin.profile.edit'),
              UserRole::VENDOR => route('vendor.profile.edit'),
              UserRole::CUSTOMER => route('customer.profile.edit'),
              default => '#',
          };
      }

      function getIconForLink($label)
      {
          return match ($label) {
              'Users' => 'heroicon-o-users',
              'Stores' => 'heroicon-o-building-storefront',
              'Products' => 'heroicon-o-shopping-bag',
              'Categories' => 'heroicon-o-tag',
              'Orders' => 'heroicon-o-clipboard-document-list',
              'Payouts' => 'heroicon-o-banknotes',
              'Vendor Earnings' => 'heroicon-o-currency-dollar',
              'Conversations' => 'heroicon-o-chat-bubble-left-right',
              'Store' => 'heroicon-o-building-storefront',
              'Create Store' => 'heroicon-o-plus-circle',
              'Reviews' => 'heroicon-o-star',
              'Stripe Account' => 'heroicon-o-credit-card',
              'Browse Products' => 'heroicon-o-shopping-bag',
              'My Orders' => 'heroicon-o-clipboard-document-list',
              'Shopping Cart' => 'heroicon-o-shopping-cart',
              default => '',
          };
      }

      function linksForRole($role, $user): array
      {
          return match ($role) {
              UserRole::ADMIN->value => [
                  [
                      'label' => 'Users',
                      'route' => route('admin.users.index'),
                      'active' => request()->routeIs('admin.users.*'),
                  ],
                  [
                      'label' => 'Stores',
                      'route' => route('admin.stores.index'),
                      'active' => request()->routeIs('admin.stores.*'),
                  ],
                  [
                      'label' => 'Products',
                      'route' => route('admin.products.index'),
                      'active' => request()->routeIs('admin.products.*'),
                  ],
                  [
                      'label' => 'Categories',
                      'route' => route('admin.categories.index'),
                      'active' => request()->routeIs('admin.categories.*'),
                  ],
                  [
                      'label' => 'Orders',
                      'route' => route('admin.orders.index'),
                      'active' => request()->routeIs('admin.orders.*'),
                  ],
                  [
                      'label' => 'Payouts',
                      'route' => route('admin.payouts.index'),
                      'active' => request()->routeIs('admin.payouts.*'),
                  ],
                  [
                      'label' => 'Vendor Earnings',
                      'route' => route('admin.vendor-earnings.index'),
                      'active' => request()->routeIs('admin.vendor-earnings.*'),
                  ],
              ],
              default => [], // No links for other roles in admin navigation
          };
      }
      $cartCount = auth()->user() ? CustomerCartService::itemsCount() : GuestCartService::itemsCount();
      $roleLinks = linksForRole($role, $user);
  @endphp

<!-- Sidebar -->
<div class="flex flex-col w-48  bg-white border-r border-neutral-300">
    <div class="flex items-center justify-center h-14 shrink-0">
        <a href="{{ getUserHomeRoute() }}">
            <x-application-logo class="block h-9 w-auto fill-current text-primary" />
        </a>
    </div>
    <nav class="flex-1 flex flex-col overflow-y-auto">
        <div class="p-4 flex flex-col  space-y-2">
            @auth
                <x-ui.sidebar-nav-link :href="getUserHomeRoute()" :active="dashboard_active()">
                    <x-heroicon-o-home class="w-5 h-5 mr-2" />
                    {{ __('Dashboard') }}
                </x-ui.sidebar-nav-link>
            @endauth

            @foreach ($roleLinks as $link)
                <x-ui.sidebar-nav-link :href="$link['route']" :active="$link['active']" class="pb-2">
                    <span class="relative inline-flex items-center">
                        @if(getIconForLink($link['label']))
                            <x-dynamic-component :component="getIconForLink($link['label'])" class="w-5 h-5 mr-2" />
                        @endif
                        {{ __($link['label']) }}
                    </span>
                </x-ui.sidebar-nav-link>
            @endforeach
        </div>
    </nav>
    <div class="p-4 border-t border-neutral-200">
        @if ($user)
            <div class="text-sm font-medium text-neutral-500">{{ $user->name }}</div>
            <div class="mt-3 space-y-1">
                <x-ui.sidebar-nav-link :href="profile_route()">
                    <x-heroicon-o-user class="w-5 h-5 mr-2" />
                    {{ __('Profile') }}
                </x-ui.sidebar-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-ui.sidebar-nav-link href="{{ route('logout') }} " class="pb-2"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5 mr-2" />
                        {{ __('Log Out') }}
                    </x-ui.sidebar-nav-link>
                </form>
            </div>
        @else
            <div class="mt-3 space-y-1">
                <x-ui.sidebar-nav-link :href="route('login')">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 mr-2" />
                    Login
                </x-ui.sidebar-nav-link>
                <x-ui.sidebar-nav-link :href="route('register')">
                    <x-heroicon-o-user-plus class="w-5 h-5 mr-2" />
                    Register
                </x-ui.sidebar-nav-link>
            </div>
        @endif
    </div>
</div>