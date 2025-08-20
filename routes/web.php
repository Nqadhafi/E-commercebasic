<?php

use Illuminate\Support\Facades\Route;

// ==== Controllers (Laravel 8 perlu 'use' eksplisit) ====
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController as CustomerOrderController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

Auth::routes();
// ==== Home / Landing ====
Route::get('/', [HomeController::class,'index'])->name('landing');

// ==== Auth scaffolding (sudah ada dari laravel/ui) ====
// Auth::routes(); // aktifkan jika perlu route bawaan login/register/forgot

// ==== Katalog (public) ====
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('catalog.show');

// ==== Keranjang (guest & user) ====
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/items/{item}', [CartController::class, 'update'])->name('cart.update');      // {item} = CartItem
Route::delete('/cart/items/{item}', [CartController::class, 'remove'])->name('cart.remove');   // {item} = CartItem
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// ==== Checkout (harus login) ====
Route::middleware('auth')->group(function () {
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::get('/checkout/provinces', [CheckoutController::class, 'provinces'])->name('checkout.provinces');
Route::get('/checkout/cities', [CheckoutController::class, 'cities'])->name('checkout.cities');           // ?province_id=
Route::get('/checkout/districts', [CheckoutController::class, 'districts'])->name('checkout.districts'); // ?city_id=
Route::post('/checkout/shipping', [CheckoutController::class, 'shippingOptions'])->name('checkout.shipping');
Route::post('/checkout/place', [CheckoutController::class, 'placeOrder'])->name('checkout.place');

    Route::resource('addresses', \App\Http\Controllers\AddressController::class)->except(['show']);

    // Pesanan saya (customer)
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
});

// ==== Admin Area ====
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Produk
    Route::resource('products', AdminProductController::class)->except(['show']);

    // Orders (admin)
Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders/{order}/resend-wa', [\App\Http\Controllers\Admin\OrderController::class, 'resendWa'])->name('orders.resendWa');

    // Settings
    Route::get('settings', [AdminSettingController::class, 'show'])->name('settings.show');
    Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
