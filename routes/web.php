<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\VenkonController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;


// Route::get('/', function () {
// $client = new GuzzleHttp\Client();
// $res = $client->get('http://213.230.65.189/Invema_Test/hs/invema_API/categories', ['auth' =>  ['Venkon', 'overlord']]);
// $resp = (string) $res->getBody();
// return response()->json(json_decode($resp, true), $res->getStatusCode());
// return view('welcome');
// });

Route::get('/', [AdminAuthController::class, 'login_form'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('auth.logout');

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:web'], function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

    // orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::post('orders/{id}/destroy', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('orders/{id}/update', [OrderController::class, 'update'])->name('orders.update');
    Route::post('orders/{id}/add_product', [OrderController::class, 'add_product'])->name('orders.add_product');
    Route::post('orders/{id}/delete_product', [OrderController::class, 'delete_product'])->name('orders.delete_product');

    // brands
    Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
    Route::post('brands/{id}/update', [BrandController::class, 'update'])->name('brands.update');

    // categories
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories/{id}/update', [CategoryController::class, 'update'])->name('categories.update');

    // products
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('products/{id}/update', [ProductController::class, 'update'])->name('products.update');

    // applications
    Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::post('applications/{id}/destroy', [ApplicationController::class, 'destroy'])->name('applications.destroy');

    // banners
    Route::get('banners', [BannerController::class, 'index'])->name('banners.index');
    Route::post('banners/{id}/update', [BannerController::class, 'update'])->name('banners.update');
    Route::post('banners/store', [BannerController::class, 'store'])->name('banners.store');
    Route::post('banners/{id}/destroy', [BannerController::class, 'destroy'])->name('banners.destroy');

    // comments
    Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('comments/update/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::post('comments/{id}/destroy', [CommentController::class, 'destroy'])->name('comments.destroy');

    // posts
    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('posts/{id}/update', [PostController::class, 'update'])->name('posts.update');
    Route::post('posts/store', [PostController::class, 'store'])->name('posts.store');
    Route::post('posts/{id}/destroy', [PostController::class, 'destroy'])->name('posts.destroy');

    // upload datas from 1c
    Route::get('upload_datas', [VenkonController::class, 'upload_datas'])->name('upload_datas');
    // Route::get('/categories-upload-from', [\App\Http\Controllers\CategoryController::class, 'upload_from'])->name('categories.upload_from');
    // Route::get('/brands-upload-from', [\App\Http\Controllers\BrandController::class, 'upload_from'])->name('brands.upload_from');
    // Route::get('/products-upload-from', [\App\Http\Controllers\ProductController::class, 'upload_from'])->name('products.upload_from');

    // users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/{id}/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('users/{id}/show', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{id}/show/orders', [UserController::class, 'show_orders'])->name('users.show.orders');

    // warehouses
    Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');

    // get regions districts
    Route::post('get_regions_districts', [WebController::class, 'get_regions_districts']);
});


// for allIn marketplace
Route::get('/store-to-allin', [\App\Http\Controllers\WebController::class, 'store_to_allin']);

// dropzone upload files
Route::post('/upload_from_dropzone', [\App\Http\Controllers\ProductController::class, 'upload_from_dropzone']);


// venkon api
Route::group(['prefix' => 'venkon'], function () {
    Route::get('brands', function () {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://213.230.65.189/Invema_Test/hs/invema_API/brands', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('categories', function () {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://213.230.65.189/Invema_Test/hs/invema_API/categories', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('colors', function () {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://213.230.65.189/Invema_Test/hs/invema_API/colors', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('products', function () {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://213.230.65.189/Invema_Test/hs/invema_API/products', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('warehouses', function () {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://213.230.65.189/Invema_Test/hs/invema_API/warehouses', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('discount', function () {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://213.230.65.189/Invema_Test/hs/invema_API/discount', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('remainder', function () {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://213.230.65.189/Invema_Test/hs/invema_API/remainder', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });
});
