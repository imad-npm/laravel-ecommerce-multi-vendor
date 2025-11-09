<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Guest\CartItemController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');

require __DIR__.'/auth.php';

Route::post('/webhooks/{gatewayType}', [WebhookController::class, 'handle'])->name('webhooks.handle');

Route::get('orders/{order}/retry-payment', [PaymentController::class,'showRetry'])->name('customer.orders.payment.retry');
Route::post('orders/{order}/retry-payment', [PaymentController::class,'retry'])->name('customer.orders.payment.retry.process');

require __DIR__.'/admin.php';
require __DIR__.'/customer.php';
require __DIR__.'/vendor.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
});

// Guest Cart Routes
Route::prefix('cart-items')->name('guest.cart.')->group(function () {
    Route::get('/', [CartItemController::class, 'index'])->name('index');
    Route::post('/', [CartItemController::class, 'store'])->name('store');
    Route::patch('/{productId}', [CartItemController::class, 'update'])->name('update');
    Route::delete('/{productId}', [CartItemController::class, 'destroy'])->name('destroy');
});
