<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminLocationController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/category/{category}', [ProductController::class, 'category'])->name('products.category');

// Map routes
Route::get('/map', [MapController::class, 'index'])->name('map.index');
Route::get('/map/our-locations', [MapController::class, 'ourLocations'])->name('map.our-locations');
Route::get('/map/nearby-pharmacies', [MapController::class, 'nearbyPharmacies'])->name('map.nearby-pharmacies');
Route::get('/map/search', [MapController::class, 'search'])->name('map.search');

// Cart routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin routes (require authentication and admin role)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Products management
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/upload-image', [AdminProductController::class, 'uploadImage'])->name('products.upload-image');
    
    // Categories management
    Route::resource('categories', AdminCategoryController::class);
    
    // Orders management
    Route::resource('orders', AdminOrderController::class);
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    
    // Locations management
    Route::resource('locations', AdminLocationController::class);
    Route::get('locations/search/address', [AdminLocationController::class, 'searchAddress'])->name('locations.search-address');
});

require __DIR__.'/auth.php';
