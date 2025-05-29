<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\CartController;

// Route Publik
Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/search', [MenuController::class, 'search'])->name('menu.search');
Route::get('/menu/{idmenu}', [MenuController::class, 'show'])->name('menu.show');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak/send', [KontakController::class, 'send'])->name('kontak.send');
Route::get('/chat', [ChatController::class, 'index'])->name('chat');  // Add this line

// Route untuk pembelian menu
Route::get('/menu/{idmenu}', [MenuController::class, 'show'])->name('beli');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cart & Order Routes (Protected)
Route::middleware(['auth.pelanggan'])->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Order Routes
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/success/{idorder}', [OrderController::class, 'success'])->name('order.success');
});

// Admin Routes (tanpa auth)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('menu', AdminMenuController::class);
    Route::resource('kategori', AdminKategoriController::class);
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/{idorder}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('order.show');
    Route::put('/orders/{idorder}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('order.update-status');
    Route::resource('pelanggan', PelangganController::class);
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

// Cart Routes - Public Access (No auth required)
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart.index');
    Route::post('/cart/add', 'add')->name('cart.add');
    Route::post('/cart/update/{id}/{action}', 'updateQuantity')->name('cart.update');
    Route::delete('/cart/remove/{id}', 'removeFromCart')->name('cart.remove');
    Route::post('/cart/clear', 'clearCart')->name('cart.clear');
});

// Cart Routes - Protected (Requires auth)
Route::controller(CartController::class)->middleware('auth')->group(function () {
    Route::get('/cart/checkout', 'checkout')->name('cart.checkout');
    Route::post('/cart/process-checkout', 'processCheckout')->name('cart.process-checkout');
});

// Order Routes
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');

Route::get('/test-db', function () {
    try {
        \DB::connection()->getPdo();
        return "Database connected successfully: " . \DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "Database error: " . $e->getMessage();
    }
});




































































