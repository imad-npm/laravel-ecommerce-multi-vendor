<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
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


require __DIR__.'/admin.php';
require __DIR__.'/customer.php';
require __DIR__.'/vendor.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');

    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index'])->name('conversations.messages.index');
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('conversations.messages.store');
});

// Guest Cart Routes
Route::prefix('cart-items')->name('cart-items.')->group(function () {
    Route::get('/', [CartItemController::class, 'index'])->name('index');
    Route::post('/', [CartItemController::class, 'store'])->name('store');
    Route::patch('/{productId}', [CartItemController::class, 'update'])->name('update');
    Route::delete('/{productId}', [CartItemController::class, 'destroy'])->name('destroy');
});

Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])->name('stripe.webhook');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       