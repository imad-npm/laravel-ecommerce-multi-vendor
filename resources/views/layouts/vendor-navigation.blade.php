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
              UserRole::VENDOR->value => array_merge(
                  [
                      [
                          'label' => 'Products',
                          'route' => route('vendor.products.index'),
                          'active' => request()->routeIs('vendor.products.*'),
                      ],
                      [
                          'label' => 'Orders',
                          'route' => route('vendor.orders.index'),
                          'active' => request()->routeIs('vendor.orders.*'),
                      ],
                      [
                          'label' => 'Conversations',
                          'route' => route('conversations.index'),
                          'active' => request()->routeIs('conversations.*'),
                      ],
                  ],
                  $user->store
                      ? [
                          [
                              'label' => 'Store',
                              'route' => route('vendor.store.show'),
                              'active' => request()->routeIs('vendor.store.*'),
                          ],
                      ]
                      : [
                          [
                              'label' => 'Create Store',
                              'route' => route('vendor.store.create'),
                              'active' => request()->routeIs('vendor.store.create'),
                          ],
                      ],
                  [
                      [
                          'label' => 'Reviews',
                          'route' => route('vendor.reviews.index'),
                          'active' => request()->routeIs('vendor.reviews.*'),
                      ],
                      [
                          'label' => 'Payouts',
                          'route' => route('vendor.payouts.index'),
                          'active' => request()->routeIs('vendor.payouts.*'),
                      ],
                      [
                          'label' => 'Stripe Account',
                          'route' => route('vendor.stripe.index'),
                          'active' => request()->routeIs('vendor.stripe.*'),
                      ],
                  ],
              ),
              default => [], // No links for other roles in vendor navigation
          };
      }
      $cartCount = auth()->user() ? CustomerCartService::itemsCount() : GuestCartService::itemsCount();
      $roleLinks = linksForRole($role, $user);
  @endphp

<!-- Sidebar -->
<div class="flex flex-col w-48 bg-white border-r border-neutral-300">
    <div class="flex items-center justify-center h-16 shrink-0">
        <a href="{{ getUserHomeRoute() }}">
            <x-application-logo class="block h-9 w-auto fill-current text-primary" />
        </a>
    </div>
    <nav class="flex-1 flex flex-col overflow-y-auto">
        <div class="p-4 flex flex-col space-y-3">
            @auth
                <x-ui.nav-link :href="getUserHomeRoute()" :active="dashboard_active()">
                    <x-heroicon-o-home class="w-5 h-5 mr-2" />
                    {{ __('Dashboard') }}
                </x-ui.nav-link>
            @endauth

            @foreach ($roleLinks as $link)
                <x-ui.nav-link :href="$link['route']" :active="$link['active']">
                    <span class="relative inline-flex items-center">
                        @if(getIconForLink($link['label']))
                            <x-dynamic-component :component="getIconForLink($link['label'])" class="w-5 h-5 mr-2" />
                        @endif
                        {{ __($link['label']) }}
                    </span>
                </x-ui.nav-link>
            @endforeach
        </div>
    </nav>
    <div class="p-4 border-t border-neutral-200">
        @if ($user)
            <div class="text-sm font-medium text-neutral-500">{{ $user->name }}</div>
            <div class="mt-3 space-y-1">
                <x-ui.nav-link :href="profile_route()">
                    <x-heroicon-o-user class="w-5 h-5 mr-2" />
                    {{ __('Profile') }}
                </x-ui.nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-ui.nav-link href="{{ route('logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5 mr-2" />
                        {{ __('Log Out') }}
                    </x-ui.nav-link>
                </form>
            </div>
        @else
            <div class="mt-3 space-y-1">
                <x-ui.nav-link :href="route('login')">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 mr-2" />
                    Login
                </x-ui.nav-link>
                <x-ui.nav-link :href="route('register')">
                    <x-heroicon-o-user-plus class="w-5 h-5 mr-2" />
                    Register
                </x-ui.nav-link>
            </div>
        @endif
    </div>
</div>