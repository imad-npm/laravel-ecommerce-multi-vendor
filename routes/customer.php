<?php

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Payment\PaymentCallbackController;
use Illuminate\Support\Facades\Route;

// === Accessible à tous (guests + clients connectés) === //
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{item}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/guest-cart/update/{id}', [CartController::class, 'updateGuest'])->name('guest.cart.update');
    Route::post('/guest-cart/remove/{id}', [CartController::class, 'removeGuest'])->name('guest.cart.remove');
    
// === Routes protégées pour les clients authentifiés === //
Route::middleware(['auth','verified', 'role:customer'])->name('customer.')->group(function () {
    Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('products.review');

    // Dashboard home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shipping Addresses Management
    Route::prefix('profile/addresses')->name('profile.addresses.')->group(function () {
        Route::get('/', [ProfileController::class, 'showAddresses'])->name('index');
        Route::get('/create', [ProfileController::class, 'createAddress'])->name('create');
        Route::post('/', [ProfileController::class, 'storeAddress'])->name('store');
        Route::get('/{address}/edit', [ProfileController::class, 'editAddress'])->name('edit');
        Route::patch('/{address}', [ProfileController::class, 'updateAddress'])->name('update');
        Route::delete('/{address}', [ProfileController::class, 'destroyAddress'])->name('destroy');
    });

    // New Multi-Step Checkout Routes
    Route::get('checkout/shipping', [\App\Http\Controllers\Customer\CheckoutController::class, 'showShippingStep'])->name('checkout.shipping');
    Route::post('checkout/shipping', [\App\Http\Controllers\Customer\CheckoutController::class, 'processShippingStep'])->name('checkout.shipping.process');

    Route::get('checkout/payment', [\App\Http\Controllers\Customer\CheckoutController::class, 'showPaymentStep'])->name('checkout.payment');
    Route::post('checkout/payment', [\App\Http\Controllers\Customer\CheckoutController::class, 'processPaymentStep'])->name('checkout.payment.process');


    // Order creation (checkout)
    // Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    // Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    
    // Order management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        
        Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });

    // Generic Payment Routes
    Route::get('/payment/{order}/{gatewayType}', [\App\Http\Controllers\Payment\PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/{order}/{gatewayType}/callback', [\App\Http\Controllers\Payment\PaymentController::class, 'handleCallback'])->name('payment.callback');

    // Remove old specific payment routes
    
    // Route::get('/payment/stripe/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'create'])->name('payment.stripe.create');
    // Route::get('/payment/stripe/success/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'success'])->name('payment.stripe.success');
    // Route::get('/payment/card/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'createCard'])->name('payment.card.create');
    // Route::post('/payment/card/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'processCard'])->name('payment.card.process');
    // Route::get('/payment/paypal/checkout/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'paypalCheckout'])->name('payment.paypal.checkout');
    // Route::get('/payment/paypal/callback', [\App\Http\Controllers\Payment\PaymentController::class, 'paypalCallback'])->name('payment.paypal.callback');
});
