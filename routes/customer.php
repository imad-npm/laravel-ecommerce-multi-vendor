<?php

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\ReviewController;
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

    // Order creation (checkout)
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    
    // Order management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/retry-payment', [OrderController::class, 'retryPayment'])->name('payment.retry');
        Route::post('/{order}/retry-payment', [OrderController::class, 'processRetryPayment'])->name('payment.retry.process');
        Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });
});
