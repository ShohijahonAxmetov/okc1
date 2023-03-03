<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiscountController;
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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MailingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ZoodpayController;


Route::get('test', [VenkonController::class, 'warehouses_put']);
// Route::get('/fix_category_product', function() {
//     $category_product = DB::table('category_product')->get();
//     // dd($category_product);
//     foreach($category_product as $item) {
//         $category = App\Models\Category::where('venkon_id', $item->category_id)
//             ->first();
//         if($category) {
//             DB::table('category_product')
//                 ->where('category_id', $item->category_id)
//                 // ->first()
//                 ->update([
//                     'category_id' => $category->integration_id
//                 ]);
//         }
//         // dd(strlen($item->category_id));
//         // $integration_id = $item->integration_id;
//     }
// });

// Route::get('/fix_category_parent', function() {
//     $categories = DB::table('categories')->get();
//     // dd($categories);
//     foreach($categories as $item) {
//         $category = App\Models\Category::where('venkon_id', $item->parent_id)
//             ->first();
//         if($category) {
//             DB::table('categories')
//                 ->where('venkon_id', $item->venkon_id)
//                 ->update([
//                     'parent_id' => $category->integration_id
//                 ]);
//         }
//     }
// });

Route::post('/fargo/history', [\App\Http\Controllers\FargoController::class, 'webhook']);

// Route::get('/order_to_venkom', [WebController::class, 'order_to_venkom']);

// Route::get('/bass', function () {
//     $client = new GuzzleHttp\Client();
//     $res = $client->get('http://213.230.65.189/invema/hs/invema_API/remainder', ['auth' =>  ['Venkon', 'overlord']]);
//     $resp = (string) $res->getBody();
//     return response()->json(json_decode($resp, true), $res->getStatusCode());
//     // return view('welcome');
// });

Route::get('/bassnew', function () {
    $client = new GuzzleHttp\Client();
    $res = $client->get('http://213.230.65.189/UT_NewClean/hs/invema_API/products', ['auth' =>  ['Venkon', 'overlord']]);
    $resp = (string) $res->getBody();
    return response()->json(json_decode($resp, true), $res->getStatusCode());
    // return view('welcome');
});

// zoodpay
Route::group(['prefix' => 'zoodpay'], function () {
    Route::get('/configuration', [ZoodpayController::class, 'configuration']);
    Route::get('/', [ZoodpayController::class, 'create_transaction']);
    Route::post('/ipn', [ZoodpayController::class, 'ipn']);
    Route::get('/res', [ZoodpayController::class, 'res']);
});

// Route::post('test', function() {
//     return response(123);
// });

Route::get('/', [AdminAuthController::class, 'login_form'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('auth.logout');

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:web'], function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

    // orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/new', [OrderController::class, 'new'])->name('orders.new');
    Route::get('orders/accepted', [OrderController::class, 'accepted'])->name('orders.accepted');
    Route::get('orders/collected', [OrderController::class, 'collected'])->name('orders.collected');
    Route::get('orders/on_the_way', [OrderController::class, 'on_the_way'])->name('orders.on_the_way');
    Route::get('orders/done', [OrderController::class, 'done'])->name('orders.done');
    Route::get('orders/returned', [OrderController::class, 'returned'])->name('orders.returned');
    Route::get('orders/cancelled', [OrderController::class, 'cancelled'])->name('orders.cancelled');
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
    Route::post('products/{id}/destroy', [ProductController::class, 'destroy'])->name('products.destroy');

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

    // discounts
    Route::get('discounts', [DiscountController::class, 'index'])->name('discounts.index');
    // Route::post('categories/{id}/update', [CategoryController::class, 'update'])->name('categories.update');

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

    // admins
    Route::get('admins', [AdminController::class, 'index'])->name('admins.index');
    Route::post('admins/store', [AdminController::class, 'store'])->name('admins.store');
    Route::post('admins/{id}/update', [AdminController::class, 'update'])->name('admins.update');
    Route::post('admins/{id}/destroy', [AdminController::class, 'destroy'])->name('admins.destroy');
    Route::get('admins/{id}/show', [AdminController::class, 'show'])->name('admins.show');

    // mailing
    Route::get('mailing', [MailingController::class, 'index'])->name('mailing.index');
    Route::post('mailing/send', [MailingController::class, 'send'])->name('mailing.send');

    // sms mailing
    Route::get('sms_mailing', [MailingController::class, 'sms_index'])->name('mailing.sms.index');
    Route::post('sms_mailing/send', [MailingController::class, 'sms_send'])->name('mailing.sms.send');

    // warehouses
    Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
    Route::get('warehouses/{id}/show', [WarehouseController::class, 'show'])->name('warehouses.show');
    Route::post('warehouses/{id}/update', [WarehouseController::class, 'update'])->name('warehouses.update');
    Route::post('warehouses/{id}/select_for_fargo', [WarehouseController::class, 'select_for_fargo'])->name('warehouses.select_for_fargo');

    // remainders
    Route::get('remainders', [WarehouseController::class, 'remainders'])->name('remainders.index');

    // get regions districts
    Route::post('get_regions_districts', [WebController::class, 'get_regions_districts']);

    // ckeditor upload image
    Route::post('upload-image', [WebController::class, 'uploadImage'])->name('upload-image');

    // profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // logs
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    Route::post('logs/destroy/{id}', [LogController::class, 'destroy'])->name('logs.destroy');
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
