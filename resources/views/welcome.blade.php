<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ShopEase • Welcome</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 min-h-screen flex items-center justify-center p-6">

  <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
    
    {{-- Hero Text --}}
    <div class="space-y-6">
      <h1 class="text-4xl lg:text-5xl font-bold tracking-tight leading-tight">
        Discover the Future<br>
        <span class="text-indigo-600 dark:text-indigo-500">of Shopping.</span>
      </h1>

      <p class="text-lg text-zinc-600 dark:text-zinc-400 max-w-md">
        Explore top deals on fashion, electronics and more — no login required.
      </p>

      <div class="flex flex-wrap gap-3">
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-sm font-medium rounded-xl shadow-md hover:bg-indigo-700 transition">
          Shop Now
        </a>
        @guest
          <a href="{{ route('register') }}"
             class="inline-flex items-center px-6 py-3 border border-zinc-300 dark:border-zinc-700 text-sm font-medium rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
            Create Account
          </a>
        @endguest
      </div>
    </div>

    {{-- Hero Image --}}
    <div class="w-full">
      <div class="relative overflow-hidden rounded-2xl shadow-lg ring-1 ring-zinc-200 dark:ring-zinc-800">
        <img src="{{ asset('storage/products/random_img.jpg') }}" 
             alt="Hero Product"
             class="object-cover w-full h-auto" />
        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 to-transparent text-white text-sm">
          New arrivals every week. Limited stock.
        </div>
      </div>
    </div>
  </div>

  {{-- Auth Links --}}
  <div class="absolute top-6 right-6 text-sm">
    @if(Route::has('login'))
      @auth
        <a href="{{ url('/dashboard') }}" class="text-zinc-700 dark:text-zinc-200 hover:underline">
          Dashboard
        </a>
      @else
        <a href="{{ route('login') }}" class="text-zinc-700 dark:text-zinc-200 hover:underline">
          Log in
        </a>
        @if(Route::has('register'))
          <a href="{{ route('register') }}" class="ml-4 text-zinc-700 dark:text-zinc-200 hover:underline">
            Register
          </a>
        @endif
      @endauth
    @endif
  </div>

</body>
</html>
