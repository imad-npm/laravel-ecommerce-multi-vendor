<?php
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\ProfileController as VendorProfile;

use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\StoreController;
use App\Http\Controllers\Vendor\ReviewController;

Route::prefix('vendor')->middleware(['auth','verified', 'role:vendor'])->name('vendor.')->group(function () {


   Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
   Route::get('/store/create', [StoreController::class, 'create'])->name('store.create');
   Route::post('/store', [StoreController::class, 'store'])->name('store.store');
   Route::get('/store', [StoreController::class, 'show'])->name('store.show');
   Route::get('/store/edit', [StoreController::class, 'edit'])->name('store.edit');
   Route::put('/store', [StoreController::class, 'update'])->name('store.update'); // 👈 nouvelle
   Route::delete('/store', [StoreController::class, 'destroy'])->name('store.destroy');

   Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

   // Produits
   Route::resource('products', ProductController::class);

   Route::get('/profile', [VendorProfile::class, 'edit'])->name('profile.edit');
   Route::patch('/profile', [VendorProfile::class, 'update'])->name('profile.update');
   Route::delete('/profile', [VendorProfile::class, 'destroy'])->name('profile.destroy');

   Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

    // Payouts
    Route::resource('payouts', App\Http\Controllers\Vendor\PayoutController::class)->only(['index']);
});
