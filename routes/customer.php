<?php

use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ShippingAddressController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Payment\PaymentCallbackController;
use Illuminate\Support\Facades\Route;


// === Authenticated Customer Routes === //
Route::middleware(['auth','verified', 'role:customer'])->name('customer.')->group(function () {
    Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('products.review');

    // Dashboard home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shipping Addresses Management
    Route::resource('addresses', ShippingAddressController::class)->except(['show']);

    // Order management & Checkout
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Nested Payment Resource
    Route::resource('orders.payments', PaymentController::class)->only(['create', 'store']);

    // Generic Payment Routes
    /*Route::get('/payment/{order}/{gatewayType}', [\App\Http\Controllers\Payment\PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/{order}/{gatewayType}/callback', [\App\Http\Controllers\Payment\PaymentController::class, 'handleCallback'])->name('payment.callback');
*/
    // Remove old specific payment routes
    
    // Route::get('/payment/stripe/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'create'])->name('payment.stripe.create');
    // Route::get('/payment/stripe/success/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'success'])->name('payment.stripe.success');
    // Route::get('/payment/card/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'createCard'])->name('payment.card.create');
    // Route::post('/payment/card/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'processCard'])->name('payment.card.process');
    // Route::get('/payment/paypal/checkout/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'paypalCheckout'])->name('payment.paypal.checkout');
    // Route::get('/payment/paypal/callback', [\App\Http\Controllers\Payment\PaymentController::class, 'paypalCallback'])->name('payment.paypal.callback');
});
