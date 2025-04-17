<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/',[FrontController::class,'index']);
Route::get('show/{id}',[FrontController::class,'show']);
Route::get('register',[FrontController::class,'register']);
Route::get('login',[FrontController::class,'login']);
Route::get('logout',[FrontController::class,'logout']);
Route::post('/postregister', [FrontController::class, 'store']);
Route::post('/postlogin', [FrontController::class, 'postlogin']);
Route::get('beli/{idmenu}',[CartController::class,'beli']);
Route::get('hapus/{idmenu}',[CartController::class,'hapus']);
Route::get('tambah/{idmenu}',[CartController::class,'tambah']);
Route::get('kurang/{idmenu}',[CartController::class,'kurang']);
Route::get('cart',[CartController::class,'cart']);
Route::get('batal',[CartController::class,'batal']);
Route::get('checkout',[CartController::class,'checkout']);
Route::get('admin',[AuthController::class,'index']);
Route::post('admin/postlogin',[AuthController::class,'postlogin']);
Route::get('admin/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::group(['middleware' => ['CekLogin:admin']], function () {
        Route::resource('user', \App\Http\Controllers\UserController::class);
    });
    Route::group(['middleware' => ['CekLogin:kasir']], function () {
        Route::resource('order', \App\Http\Controllers\OrderController::class);
    });
    Route::group(['middleware' => ['CekLogin:manager']], function () {
        Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class);
        Route::resource('menu', \App\Http\Controllers\Admin\MenuController::class);
        Route::resource('pelanggan', \App\Http\Controllers\Admin\PelangganController::class);
        Route::resource('order', \App\Http\Controllers\Admin\OrderController::class);
        Route::resource('orderdetail',\App\Http\Controllers\Admin\OrderDetailController::class);
        Route::get('select',[Admin\MenuController::class,'select']);
        Route::post('postmenu',[Admin\MenuController::class,'update']);

    });


    Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class);
    Route::resource('user', \App\Http\Controllers\UserController::class);
    Route::resource('Order', \App\Http\Controllers\OrderController::class);


    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', function() {
        return view('Backend.dashboard');
    })->middleware('auth')->name('dashboard');
});


Route::get('/bydzexz', function () {
    return view('bydzexz');
});

Route::get('/test-users', [\App\Http\Controllers\TestController::class, 'showUsers']);

Route::get('/debug-users', function() {
    $users = \App\Models\User::all();
    return response()->json(['users' => $users]);
});

Route::get('/test-auth', function() {
    $credentials = [
        'email' => 'admin_test@example.com',
        'password' => '123'
    ];

    $authResult = \Illuminate\Support\Facades\Auth::attempt($credentials);

    return response()->json([
        'auth_result' => $authResult,
        'user' => $authResult ? \Illuminate\Support\Facades\Auth::user() : null
    ]);
});

Route::get('/reset-admin-password', function() {
    $user = \App\Models\User::where('email', 'admin_test@example.com')->first();

    if ($user) {
        $user->password = \Illuminate\Support\Facades\Hash::make('123');
        $user->save();
        return response()->json(['success' => true, 'message' => 'Password reset successfully']);
    }

    return response()->json(['success' => false, 'message' => 'User not found']);
});

Route::get('/create-new-admin', function() {
    try {
        $user = \App\Models\User::create([
            'name' => 'Admin New',
            'email' => 'admin_new_' . time() . '@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('123'),
            'level' => 'admin'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'New admin created successfully',
            'user' => $user,
            'login_url' => url('/admin'),
            'email' => $user->email,
            'password' => '123'
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
});

Route::get('/check-admin-users', function() {
    $adminUsers = \App\Models\User::where('level', 'admin')->get();

    return response()->json([
        'admin_users' => $adminUsers,
        'count' => $adminUsers->count()
    ]);
});
