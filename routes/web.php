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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:web'], function() {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    
    // orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

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
    
    // comments
    Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('comments/update/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::post('comments/{id}/destroy', [CommentController::class, 'destroy'])->name('comments.destroy');

    // upload datas from 1c
    Route::post('upload_datas', [VenkonController::class, 'upload_datas'])->name('upload_datas');
});


// upload updates
Route::get('/categories-upload-from', [\App\Http\Controllers\CategoryController::class, 'upload_from'])->name('categories.upload_from');
Route::get('/brands-upload-from', [\App\Http\Controllers\BrandController::class, 'upload_from'])->name('brands.upload_from');
Route::get('/products-upload-from', [\App\Http\Controllers\ProductController::class, 'upload_from'])->name('products.upload_from');

// for allIn marketplace
Route::get('/store-to-allin', [\App\Http\Controllers\WebController::class, 'store_to_allin']);

Route::post('/upload_from_dropzone', [\App\Http\Controllers\ProductController::class, 'upload_from_dropzone']);
