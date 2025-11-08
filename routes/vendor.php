<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\{
    DashboardController,
    OrderController,
    ProductController,
    StoreController,
    ReviewController,
    ProfileController as VendorProfile,
    PayoutController
};

Route::prefix('vendor')
    ->middleware(['auth', 'verified', 'role:vendor'])
    ->name('vendor.')
    ->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Store (single instance → use singleton)
        Route::singleton('store', StoreController::class);

        // Orders
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

        // Products (full CRUD)
        Route::resource('products', ProductController::class);

        // Profile (single instance → singleton)
        Route::singleton('profile', VendorProfile::class)->only(['edit', 'update', 'destroy']);

        // Reviews
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');

        // Payouts
        Route::resource('payouts', PayoutController::class)->only(['index']);
    });
