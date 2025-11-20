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
                  ],
              ),
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


  <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">

      <!-- Top bar -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
              <!-- Left -->
              <div class="flex">
                  <!-- Logo -->
                  <div class="shrink-0 flex items-center">
                      <a href="{{ getUserHomeRoute() }}">
                          <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                      </a>
                  </div>

                  <!-- Desktop Links -->
                  <div class="hidden sm:flex space-x-8 sm:ms-10">
                    @auth
                       <x-ui.nav-link :href="getUserHomeRoute()" :active="dashboard_active()">
                          {{ __('Dashboard') }}
                      </x-ui.nav-link>

                    @endauth
                    
                      @foreach ($roleLinks as $link)
                          <x-ui.nav-link :href="$link['route']" :active="$link['active']">
                              <span class="relative inline-flex items-center">
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
                              class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
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
                                  {{ __('Profile') }}
                              </x-ui.dropdown-link>

                              <form method="POST" action="{{ route('logout') }}">
                                  @csrf
                                  <x-ui.dropdown-link href="{{ route('logout') }}"
                                      onclick="event.preventDefault(); this.closest('form').submit();">
                                      {{ __('Log Out') }}
                                  </x-ui.dropdown-link>
                              </form>
                          @else
                              <x-ui.dropdown-link :href="route('login')">Login</x-ui.dropdown-link>
                              <x-ui.dropdown-link :href="route('register')">Register</x-ui.dropdown-link>
                          @endif
                      </x-slot>
                  </x-ui.dropdown>
              </div>

              <!-- Hamburger -->
              <div class="-me-2 flex items-center sm:hidden">
                  <button @click="open = ! open"
                      class="p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 rounded-md">
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
                  {{ __('Dashboard') }}
              </x-ui.responsive-nav-link>

            @endauth
          
              @foreach ($roleLinks as $link)
                  <x-ui.responsive-nav-link :href="$link['route']" :active="$link['active']">
                      {{ __($link['label']) }}
                  </x-ui.responsive-nav-link>
              @endforeach
          </div>

          <!-- Mobile User Info -->
          <div class="pt-4 pb-1 border-t border-gray-200">
              <div class="px-4">
                  @if ($user)
                      <div class="text-base text-gray-800">{{ $user->name }}</div>
                      <div class="text-sm text-gray-500">{{ $user->email }}</div>
                  @else
                      <div class="text-base text-gray-800">Guest</div>
                  @endif
              </div>

              <div class="mt-3 space-y-1">
                  @if ($user)
                      <x-ui.responsive-nav-link :href="profile_route()">
                          {{ __('Profile') }}
                      </x-ui.responsive-nav-link>

                      <form method="POST" action="{{ route('logout') }}">
                          @csrf
                          <x-ui.responsive-nav-link href="{{ route('logout') }}"
                              onclick="event.preventDefault(); this.closest('form').submit();">
                              {{ __('Log Out') }}
                          </x-ui.responsive-nav-link>
                      </form>
                  @else
                      <x-ui.responsive-nav-link :href="route('login')">Login</x-ui.responsive-nav-link>
                      <x-ui.responsive-nav-link :href="route('register')">Register</x-ui.responsive-nav-link>
                  @endif
              </div>
          </div>
      </div>
  </nav>
