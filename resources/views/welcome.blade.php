<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ShopEase • Your Modern Marketplace</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray text-gray dark:text-gray font-sans antialiased">

  <!-- Navigation -->
  <header class="fixed top-0 left-0 right-0 z-50 bg-white/90 dark:bg-gray/90 backdrop-blur-sm px-8 py-4 flex justify-between items-center shadow-sm">
    <a href="/" class="text-2xl font-bold tracking-tight transition-colors hover:text-primary dark:hover:text-primary">
      Shop<span class="text-primary dark:text-primary">Ease</span>
    </a>
    <nav class="flex items-center space-x-4 text-sm">
      @if(Route::has('login'))
        @auth
          <x-ui.link variant="default" href="{{ route('customer.home') }}">Dashboard</x-ui.link>
        @else
          <x-ui.link variant="default" href="{{ route('login') }}">Log in</x-ui.link>
          @if(Route::has('register'))
            <x-ui.link variant="default" href="{{ route('register') }}">Sign Up</x-ui.link>
          @endif
        @endauth
      @endif
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="relative flex items-center justify-center px-6 pt-32 pb-20 bg-gradient-to-b from-primary/5 dark:from-gray">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      
      <!-- Text Content -->
      <div class="space-y-6 lg:space-y-8">
        <h1 class="text-4xl lg:text-6xl font-extrabold leading-tight tracking-tight">
          Your Marketplace,<br>
          <span class="text-primary dark:text-primary">Simplified.</span>
        </h1>
        <p class="text-lg text-gray dark:text-gray max-w-md leading-relaxed">
          Shop or sell effortlessly with our modern multi-vendor marketplace. 
          Discover products you love, manage your store with ease, and enjoy seamless transactions.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="{{ route('products.index') }}" class="inline-flex items-center px-8 py-3 bg-primary hover:bg-primary text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transition-all">
            Start Shopping
          </a>
          @guest
            <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3 border border-gray dark:border-gray text-gray dark:text-gray font-medium rounded-2xl hover:bg-gray dark:hover:bg-gray transition">
              Create Account
            </a>
          @endguest
        </div>
      </div>

      <!-- Hero Image -->
      <div class="w-full rounded-3xl overflow-hidden shadow-2xl ring-1 ring-gray dark:ring-gray transition-transform hover:scale-[1.02]">
        <img src="{{ asset('storage/products/random_img.jpg') }}" alt="Featured Product" class="object-cover w-full h-full max-h-[600px]"/>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="py-24 bg-white dark:bg-gray">
    <div class="max-w-6xl mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold mb-16 text-gray dark:text-gray">Why ShopEase?</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
        <div class="p-8 rounded-3xl bg-white dark:bg-gray shadow-lg hover:shadow-2xl transition-all duration-300">
          <h3 class="font-semibold text-xl mb-3 text-primary dark:text-primary">Discover Anything</h3>
          <p class="text-gray dark:text-gray text-sm">
            Explore a wide variety of products across fashion, electronics, home, and more.
          </p>
        </div>
        <div class="p-8 rounded-3xl bg-white dark:bg-gray shadow-lg hover:shadow-2xl transition-all duration-300">
          <h3 class="font-semibold text-xl mb-3 text-primary dark:text-primary">Sell With Ease</h3>
          <p class="text-gray dark:text-gray text-sm">
            Vendors manage their store, products, and orders effortlessly — all in one place.
          </p>
        </div>
        <div class="p-8 rounded-3xl bg-white dark:bg-gray shadow-lg hover:shadow-2xl transition-all duration-300">
          <h3 class="font-semibold text-xl mb-3 text-primary dark:text-primary">Fast & Secure</h3>
          <p class="text-gray dark:text-gray text-sm">
            Seamless transactions and secure payments ensure a smooth experience for everyone.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works -->
  <section class="py-24 bg-primary dark:bg-gray">
    <div class="max-w-5xl mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold mb-16 text-white dark:text-gray">How It Works</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
        <div class="p-8 rounded-3xl bg-white dark:bg-gray shadow-lg hover:shadow-2xl transition-all duration-300">
          <h3 class="font-semibold text-xl mb-2 text-primary dark:text-primary">1. Browse Products</h3>
          <p class="text-gray dark:text-gray text-sm">
            Find what you love from thousands of products from multiple vendors.
          </p>
        </div>
        <div class="p-8 rounded-3xl bg-white dark:bg-gray shadow-lg hover:shadow-2xl transition-all duration-300">
          <h3 class="font-semibold text-xl mb-2 text-primary dark:text-primary">2. Place Orders</h3>
          <p class="text-gray dark:text-gray text-sm">
            Add to cart, checkout easily, and track your order in real-time.
          </p>
        </div>
        <div class="p-8 rounded-3xl bg-white dark:bg-gray shadow-lg hover:shadow-2xl transition-all duration-300">
          <h3 class="font-semibold text-xl mb-2 text-primary dark:text-primary">3. Manage Your Store</h3>
          <p class="text-gray dark:text-gray text-sm">
            Vendors can add products, process orders, and interact with customers effortlessly.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="py-24 bg-white dark:bg-gray">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold mb-16 text-gray dark:text-gray">What Our Users Say</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <div class="p-8 rounded-3xl bg-white dark:bg-gray shadow-lg hover:shadow-2xl transition-all duration-300">
          <p class="text-gray dark:text-gray italic">"ShopEase made selling my products so easy — I can manage everything in one place!"</p>
          <p class="mt-4 font-semibold text-gray dark:text-gray">— Sarah, Vendor</p>
        </div>
        <div class="p-8 rounded-3xl bg-white dark:bg-gray shadow-lg hover:shadow-2xl transition-all duration-300">
          <p class="text-gray dark:text-gray italic">"I love discovering unique products and the checkout is super smooth."</p>
          <p class="mt-4 font-semibold text-gray dark:text-gray">— Mike, Customer</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Call-to-Action -->
  <section class="py-24 bg-primary dark:bg-primary text-white text-center">
    <h2 class="text-3xl lg:text-4xl font-extrabold mb-6">Ready to start?</h2>
    <p class="mb-8 text-lg max-w-xl mx-auto">Create your account or explore our marketplace instantly. Experience simplicity and speed in one platform.</p>
    <div class="flex justify-center gap-6 flex-wrap">
      <a href="{{ route('register') }}" class="px-10 py-4 bg-white text-primary rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all">
        Create Account
      </a>
      <a href="{{ route('products.index') }}" class="px-10 py-4 border border-white rounded-2xl hover:bg-white hover:text-primary transition-all">
        Browse Marketplace
      </a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-12 bg-gray dark:bg-gray text-gray dark:text-gray text-center text-sm">
    &copy; {{ date('Y') }} ShopEase. All rights reserved.
  </footer>

</body>
</html>
