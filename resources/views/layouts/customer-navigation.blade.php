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
              UserRole::CUSTOMER->value => [
                  [
                      'label' => 'Browse Products',
                      'route' => route('products.index'),
                      'active' => request()->routeIs('products.*'),
                  ],
                  [
                      'label' => 'My Orders',
                      'route' => route('customer.orders.index'),
                      'active' => request()->routeIs('customer.orders.*'),
                  ],
                  [
                      'label' => 'Shopping Cart',
                      'route' => route('customer.cart-items.index'),
                      'active' => request()->routeIs('cart-items.*'),
                  ], // Updated
                  [
                      'label' => 'Conversations',
                      'route' => route('conversations.index'),
                      'active' => request()->routeIs('conversations.*'),
                  ],
              ],
              default => [
                  // guest
                  [
                      'label' => 'Browse Products',
                      'route' => route('products.index'),
                      'active' => request()->routeIs('products.index'),
                  ],
                  ['label' => 'Login', 'route' => route('login'), 'active' => request()->routeIs('login')],
                  ['label' => 'Register', 'route' => route('register'), 'active' => request()->routeIs('register')],
                  [
                      'label' => 'Shopping Cart',
                      'route' => route('cart-items.index'),
                      'active' => request()->routeIs('cart-items.*'),
                  ], // Updated
              ],
          };
      }
      $cartCount = auth()->user() ? CustomerCartService::itemsCount() : GuestCartService::itemsCount();
      $roleLinks = linksForRole($role, $user);
  @endphp


  <nav x-data="{ open: false }" class="bg-white border-b border-neutral-300">

      <!-- Top bar -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
              <!-- Left -->
              <div class="flex">
                  <!-- Logo -->
                  <div class="shrink-0 flex items-center">
                      <a href="{{ getUserHomeRoute() }}">
                          <x-application-logo class="block h-9 w-auto fill-current text-primary" />
                      </a>
                  </div>

                  <!-- Desktop Links -->
                  <div class="hidden sm:flex space-x-8 sm:ms-10">
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
                                  @if ($link['label'] === 'Shopping Cart' && $cartCount > 0)
                                      <span
                                          class="ml-1.5 relative inline-flex w-5 h-5 text-xs items-center justify-center bg-primary text-white font-semibold rounded-full ring-2 ring-white shadow">
                                          {{ $cartCount }}
                                      </span>
                                  @endif
                              </span>
                          </x-ui.nav-link>
                      @endforeach


                  </div>
              </div>

              <!-- Right -->
              <div class="hidden sm:flex sm:items-center sm:ms-6">
                  <x-ui.dropdown align="right" width="48">
                      <x-slot name="trigger">
                          <button
                              class="inline-flex items-center px-3 py-2 text-sm font-medium text-neutral-500 bg-white hover:text-neutral-700 focus:outline-none transition">
                              <div>{{ $user?->name ?? 'Guest' }}</div>
                              <div class="ms-1">
                                  <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                      <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414L10 13.414l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd" />
                                  </svg>
                              </div>
                          </button>
                      </x-slot>

                      <x-slot name="content">
                          @if ($user)
                              <x-ui.dropdown-link :href="profile_route()">
                                  <x-heroicon-o-user class="w-5 h-5 mr-2" />
                                  {{ __('Profile') }}
                              </x-ui.dropdown-link>

                              <form method="POST" action="{{ route('logout') }}">
                                  @csrf
                                  <x-ui.dropdown-link href="{{ route('logout') }}"
                                      onclick="event.preventDefault(); this.closest('form').submit();">
                                      <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5 mr-2" />
                                      {{ __('Log Out') }}
                                  </x-ui.dropdown-link>
                              </form>
                          @else
                              <x-ui.dropdown-link :href="route('login')">
                                <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 mr-2" />
                                Login
                              </x-ui.dropdown-link>
                              <x-ui.dropdown-link :href="route('register')">
                                <x-heroicon-o-user-plus class="w-5 h-5 mr-2" />
                                Register
                              </x-ui.dropdown-link>
                          @endif
                      </x-slot>
                  </x-ui.dropdown>
              </div>

              <!-- Hamburger -->
              <div class="-me-2 flex items-center sm:hidden">
                  <button @click="open = ! open"
                      class="p-2 text-neutral-400 hover:text-neutral-500 hover:bg-neutral-100 rounded-md">
                      <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24">
                          <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                              stroke="currentColor" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                          <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke="currentColor"
                              stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                  </button>
              </div>
          </div>
      </div>

      <!-- Mobile Menu -->
      <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
          <div class="pt-2 pb-3 space-y-1">
            @auth
                    <x-ui.responsive-nav-link :href="getUserHomeRoute()" :active="dashboard_active()">
                  <x-heroicon-o-home class="w-5 h-5 mr-2" />
                  {{ __('Dashboard') }}
              </x-ui.responsive-nav-link>

            @endauth
          
              @foreach ($roleLinks as $link)
                  <x-ui.responsive-nav-link :href="$link['route']" :active="$link['active']">
                      @if(getIconForLink($link['label']))
                          <x-dynamic-component :component="getIconForLink($link['label'])" class="w-5 h-5 mr-2" />
                      @endif
                      {{ __($link['label']) }}
                  </x-ui.responsive-nav-link>
              @endforeach
          </div>

          <!-- Mobile User Info -->
          <div class="pt-4 pb-1 border-t border-neutral-200">
              <div class="px-4">
                  @if ($user)
                      <div class="text-base text-primary">{{ $user->name }}</div>
                      <div class="text-sm text-neutral-500">{{ $user->email }}</div>
                  @else
                      <div class="text-base text-primary">Guest</div>
                  @endif
              </div>

              <div class="mt-3 space-y-1">
                  @if ($user)
                      <x-ui.responsive-nav-link :href="profile_route()">
                          <x-heroicon-o-user class="w-5 h-5 mr-2" />
                          {{ __('Profile') }}
                      </x-ui.responsive-nav-link>

                      <form method="POST" action="{{ route('logout') }}">
                          @csrf
                          <x-ui.responsive-nav-link href="{{ route('logout') }}"
                              onclick="event.preventDefault(); this.closest('form').submit();">
                              <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5 mr-2" />
                              {{ __('Log Out') }}
                          </x-ui.responsive-nav-link>
                      </form>
                  @else
                      <x-ui.responsive-nav-link :href="route('login')">
                        <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 mr-2" />
                        Login
                      </x-ui.responsive-nav-link>
                      <x-ui.responsive-nav-link :href="route('register')">
                        <x-heroicon-o-user-plus class="w-5 h-5 mr-2" />
                        Register
                      </x-ui.responsive-nav-link>
                  @endif
              </div>
          </div>
      </div>
  </nav>
