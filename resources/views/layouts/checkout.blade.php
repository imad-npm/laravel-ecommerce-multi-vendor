<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    <div class="w-1/3">
                        <div class="text-center">
                            <div class="mx-auto h-10 w-10 rounded-full flex items-center justify-center {{ request()->routeIs('customer.checkout.shipping') ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600' }}">1</div>
                            <p class="mt-2 text-sm font-medium">Shipping</p>
                        </div>
                    </div>
                    <div class="flex-1 h-1 {{ request()->routeIs('customer.checkout.payment') ? 'bg-blue-500' : 'bg-gray-300' }}"></div>
                    <div class="w-1/3">
                        <div class="text-center">
                            <div class="mx-auto h-10 w-10 rounded-full flex items-center justify-center {{ request()->routeIs('customer.checkout.payment') ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600' }}">2</div>
                            <p class="mt-2 text-sm font-medium">Payment</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-lg shadow-md">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
