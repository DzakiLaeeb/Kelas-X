<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Route untuk homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes untuk shop dan products
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::post('/search', [ShopController::class, 'search'])->name('search');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Routes untuk tentang kami dan hubungi kami
Route::get('/tentangkami', function () {
    return view('about');
})->name('tentangkami');

Route::get('/hubungikami', function () {
    return view('contact');
})->name('hubungikami');

// Routes untuk cart
Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
Route::post('/cart/remove', [OrderController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
Route::get('/order/success/{order}', [OrderController::class, 'orderSuccess'])->name('order.success');

// Routes untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Routes untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User profile
    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\UserController::class, 'updateProfile'])->name('profile.update');

    // User orders
    Route::get('/orders', [\App\Http\Controllers\UserController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [\App\Http\Controllers\UserController::class, 'showOrder'])->name('orders.show');

    // User settings
    Route::get('/settings', [\App\Http\Controllers\UserController::class, 'settings'])->name('settings');
    Route::put('/settings/password', [\App\Http\Controllers\UserController::class, 'updatePassword'])->name('settings.password');
});

// Test routes
Route::get('/test', [\App\Http\Controllers\TestController::class, 'index']);
Route::get('/admin-test', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);

// Routes untuk admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');

    // Orders
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Users
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Banners
    Route::get('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banners.index');
    Route::get('/banners/create', [\App\Http\Controllers\Admin\BannerController::class, 'create'])->name('banners.create');
    Route::post('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'store'])->name('banners.store');
    Route::get('/banners/{banner}/edit', [\App\Http\Controllers\Admin\BannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('banners.destroy');
});


