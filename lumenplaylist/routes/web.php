<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// API Routes Group
$router->group(['prefix' => 'api', 'namespace' => 'API'], function () use ($router) {
    // Public routes
    $router->post('register', 'LoginController@register');
    $router->post('login', 'LoginController@login');
    
    // Protected routes
    $router->group(['middleware' => 'auth'], function () use ($router) {
        // Auth routes
        $router->post('logout', 'LoginController@logout');
    
        // Category Routes
        $router->group(['prefix' => 'kategori'], function () use ($router) {
            $router->get('/', 'CategoryController@index');
            $router->post('/', 'CategoryController@store');
            $router->get('/{id}', 'CategoryController@show');
            $router->put('/{id}', 'CategoryController@update');
            $router->delete('/{id}', 'CategoryController@destroy');
        });

        // Menu Routes
        $router->group(['prefix' => 'menu'], function () use ($router) {
            $router->get('/', 'MenuController@index');
            $router->get('/search', 'MenuController@search');
            $router->post('/', 'MenuController@store');
            $router->get('/{id}', 'MenuController@show');
            $router->put('/{id}', 'MenuController@update');
            $router->delete('/{id}', 'MenuController@destroy');
        });

        // Customer Routes
        $router->group(['prefix' => 'pelanggan'], function () use ($router) {
            $router->get('/', 'PelangganController@index');
            $router->post('/', 'PelangganController@store');
            $router->get('/{id}', 'PelangganController@show');
            $router->put('/{id}', 'PelangganController@update');
            $router->delete('/{id}', 'PelangganController@destroy');
        });
    });
});
