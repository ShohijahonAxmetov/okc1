<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AddressInfoController,
    InfoController,
    ReviewController,
    PageController,
    DiscountController,
    DashboardController,
    OrderController,
    BrandController,
    CategoryController,
    ProductController,
    ApplicationController,
    BannerController,
    CommentController,
    VenkonController,
    AdminAuthController,
    WebController,
    PostController,
    UserController,
    WarehouseController,
    AdminController,
    MailingController,
    ProfileController,
    LogController,
    ZoodpayController,
    Express24Controller,
    TelegramBotController,
    Yandex\YandexMarketController,
    Yandex\YandexDeliveryController,
    Yandex\YandexEatsController,
};

$new_ip_address = '94.232.24.102';

// Route::get('ter', [VenkonController::class, 'upload_datas'])->name('upload_datas');

Route::get('regherherhe', [Express24Controller::class, 'updateTest']);

Route::post('/fargo/history', [\App\Http\Controllers\FargoController::class, 'webhook']);

// Route::get('/order_to_venkom', [WebController::class, 'order_to_venkom']);

// zoodpay
Route::group(['prefix' => 'zoodpay'], function () {
    Route::get('/configuration', [ZoodpayController::class, 'configuration']);
    Route::get('/', [ZoodpayController::class, 'create_transaction']);
    Route::post('/ipn', [ZoodpayController::class, 'ipn']);
    Route::get('/res', [ZoodpayController::class, 'res']);
});

Route::get('/', [AdminAuthController::class, 'login_form'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('auth.login');
Route::get('/login', function() {return abort(404);});
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
    Route::get('products/to-excel', [ProductController::class, 'toExcel'])->name('products.to_excel');

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

    // PAGES
    Route::prefix('pages')->group(function() {
        Route::get('about', [PageController::class, 'about'])->name('about');
        Route::get('contacts', [PageController::class, 'contacts'])->name('contacts');
        Route::get('delivery', [PageController::class, 'delivery'])->name('delivery');

        Route::post('{id}/update', [PageController::class, 'update'])->name('pages.update');
    });

    // REVIEWS
    Route::resource('reviews', ReviewController::class);

    // INFOS
    Route::resource('infos', InfoController::class);    
    Route::resource('addresses', AddressInfoController::class);

    Route::prefix('integrations')->group(function () {
        Route::get('express24', [Express24Controller::class, 'index'])->name('integrations.express24.index');
        Route::get('express24/categories', [Express24Controller::class, 'toCategoriesPage'])->name('integrations.express24.categories');
        Route::post('express24/categories', [Express24Controller::class, 'updateCategory'])->name('integrations.express24.categories.update');

        Route::get('express24/branches', [Express24Controller::class, 'toBranchesPage'])->name('integrations.express24.branches');
        Route::post('express24/branches', [Express24Controller::class, 'updateBranch'])->name('integrations.express24.branches.update');

        Route::get('express24/products', [Express24Controller::class, 'toProductsPage'])->name('integrations.express24.products');
        Route::post('express24/products', [Express24Controller::class, 'updateProduct'])->name('integrations.express24.products.update');

        Route::get('express24/config', [Express24Controller::class, 'toConfigPage'])->name('integrations.express24.config');
        Route::post('express24/config', [Express24Controller::class, 'updateConfig'])->name('integrations.express24.config.update');

        Route::get('bot', [TelegramBotController::class, 'index'])->name('integrations.bot.index');
        Route::get('messages', [TelegramBotController::class, 'messages'])->name('integrations.bot.messages');
        Route::get('messages/create', [TelegramBotController::class, 'messageCreate'])->name('integrations.bot.messages.create');
        Route::get('messages/{message}', [TelegramBotController::class, 'message'])->name('integrations.bot.message');
        Route::post('messages/{message}/send', [TelegramBotController::class, 'realSend'])->name('integrations.bot.message.send');
        Route::post('messages', [TelegramBotController::class, 'sendMessage'])->name('integrations.bot.messages_send');
        Route::get('users', [TelegramBotController::class, 'users'])->name('integrations.bot.users');
        Route::get('feedback', [TelegramBotController::class, 'feedback'])->name('integrations.bot.feedback');

        // ------------------------------------------------------------------------------------------------

        Route::prefix('yandex_market')->name('integrations.yandex_market.')->group(function () {
            Route::get('/', [YandexMarketController::class, 'index'])->name('index');
            Route::get('categories', [YandexMarketController::class, 'categories'])->name('categories');
            Route::get('branches', [YandexMarketController::class, 'branches'])->name('branches');
            Route::get('config', [YandexMarketController::class, 'config'])->name('config');
            Route::post('pin_category', [YandexMarketController::class, 'pinCategory'])->name('pin_category');
            Route::get('products', [YandexMarketController::class, 'products'])->name('products');
            Route::post('product_characteristics', [YandexMarketController::class, 'productCharacteristics'])->name('product_characteristics');
        });

        Route::prefix('yandex_delivery')->name('integrations.yandex_delivery.')->group(function () {
            Route::get('/', [YandexDeliveryController::class, 'index'])->name('index');
            Route::get('/orders', [YandexDeliveryController::class, 'orders'])->name('orders');
            Route::get('/orders/create', [YandexDeliveryController::class, 'orderCreate'])->name('orders.create');
            // Route::post('/orders', [YandexDeliveryController::class, 'orderStore'])->name('orders.store');
            Route::post('/order', [YandexDeliveryController::class, 'order'])->name('order');
            Route::get('/loading_points', [YandexDeliveryController::class, 'loadingPoints'])->name('loading_points');
            Route::get('/loading_points/create', [YandexDeliveryController::class, 'loadingPointsCreate'])->name('loading_points.create');
            Route::post('/loading_points', [YandexDeliveryController::class, 'loadingPointsStore'])->name('loading_points.store');
            Route::put('/loading_points/{id}', [YandexDeliveryController::class, 'loadingPointsUpdate'])->name('loading_points.update');
            Route::delete('/loading_points/{id}', [YandexDeliveryController::class, 'loadingPointsDelete'])->name('loading_points.delete');
        });

        Route::prefix('yandex_eats')->name('integrations.yandex_eats.')->group(function () {
            Route::get('/', [YandexEatsController::class, 'index'])->name('index');
            Route::get('/products', [YandexEatsController::class, 'products'])->name('products');
            Route::get('/products/{product}', [YandexEatsController::class, 'productEdit'])->name('products.edit');
            Route::put('/products/{product}', [YandexEatsController::class, 'productUpdate'])->name('products.update');
            Route::get('/orders', [YandexEatsController::class, 'orders'])->name('orders');
            Route::get('/orders/{order}', [YandexEatsController::class, 'orderEdit'])->name('orders.edit');
            Route::put('/orders/{order}', [YandexEatsController::class, 'orderUpdate'])->name('orders.update');
        });
    });

    Route::get('blocked_ip_addresses', [\App\Http\Controllers\BlockedIpAddressController::class, 'index'])->name('blocked_ip_addresses.index');
    Route::delete('blocked_ip_addresses/{id}', [\App\Http\Controllers\BlockedIpAddressController::class, 'destroy'])->name('blocked_ip_addresses.destroy');
});


// for allIn marketplace
Route::get('/store-to-allin', [\App\Http\Controllers\WebController::class, 'store_to_allin']);

// dropzone upload files
Route::post('/upload_from_dropzone', [\App\Http\Controllers\ProductController::class, 'upload_from_dropzone']);


// venkon api
Route::group(['prefix' => 'venkon'], function () use ($new_ip_address) {
    Route::get('brands', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/Invema_Test/hs/invema_API/brands', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('categories', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/Invema_Test/hs/invema_API/categories', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('colors', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/Invema_Test/hs/invema_API/colors', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('products', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/Invema_Test/hs/invema_API/products', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('warehouses', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/Invema_Test/hs/invema_API/warehouses', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('discount', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/Invema_Test/hs/invema_API/discount', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('remainder', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/Invema_Test/hs/invema_API/remainder', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });
});

Route::get('rss/catalog', [\App\Http\Controllers\RssController::class, 'catalog']);
Route::get('express24/catalog', [\App\Http\Controllers\Express24Controller::class, 'categories']);