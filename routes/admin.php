<?php
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController ;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StoreController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 Route::resource('orders', OrderController::class)->except(['create', 'store', 'destroy']);
  
 Route::resource('users', UserController::class);
Route::resource('products', ProductController::class);
Route::resource('stores', StoreController::class);
Route::resource('categories', CategoryController::class);

    // Route pour annuler la commande (PATCH)
    Route::patch('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});
