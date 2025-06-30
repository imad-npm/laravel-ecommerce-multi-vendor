<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProfileController as AdminProfile;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Vendor\ProfileController as VendorProfile;
use App\Http\Controllers\Customer\ProfileController as CustomerProfile;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ChatController;

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/profile', [AdminProfile::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AdminProfile::class, 'update'])->name('profile.update');
    Route::delete('/profile', [AdminProfile::class, 'destroy'])->name('profile.destroy');
});




Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');




require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/customer.php';
require __DIR__.'/vendor.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
});
