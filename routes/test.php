<?php

use Illuminate\Support\Facades\Route;

// Route::get('yandex-market-test', [\App\Http\Controllers\Yandex\YandexMarketController::class, 'sendProducts2Market']);
// Route::get('yandex-market-dfjhdfjdfj', [\App\Http\Controllers\Yandex\YandexMarketController::class, 'getCategories']);
Route::get('yandex-market-test-remainders', [\App\Http\Controllers\Yandex\YandexMarketController::class, 'sendRemainds2Market']);
// Route::get('translate-test', [\App\Http\Controllers\TestController::class, 'translate']);
Route::get('delivery-journal', [\App\Http\Controllers\Yandex\YandexDeliveryController::class, 'getClaimsInfo']);
Route::get('yatext', [\App\Http\Controllers\Yandex\YandexMarketController::class, 'getProductsInfo']);

Route::get('express24', [\App\Http\Controllers\Express24Controller::class, 'categories']);
// routes for test venkon API

Route::prefix('for_test')->group(function() {
    // $new_ip_address = '94.232.24.102'; //06.03.2023
    $new_ip_address = '213.230.65.189';

    Route::get('brands', function() use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/UT_NewClean/hs/invema_API/brands', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('categories', function() use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/UT_NewClean/hs/invema_API/categories', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('colors', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/UT_NewClean/hs/invema_API/colors', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('warehouses', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/UT_NewClean/hs/invema_API/warehouses', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('products', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/UT_NewClean/hs/invema_API/products', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('discount', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/UT_NewClean/hs/invema_API/discount', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });

    Route::get('remainder', function () use ($new_ip_address) {
        $client = new GuzzleHttp\Client();
        $res = $client->get('http://' . $new_ip_address . '/UT_NewClean/hs/invema_API/remainder', ['auth' =>  ['Venkon', 'overlord']]);
        $resp = (string) $res->getBody();
        return response()->json(json_decode($resp, true), $res->getStatusCode());
    });
});


Route::get('test/loyalty', [\App\Http\Controllers\TestController::class, 'loyalty']);
Route::get('test/delivery', [\App\Http\Controllers\TestController::class, 'delivery']);

// Route::get('img2webp-test', [\App\Http\Controllers\TestController::class, 'img2webp']);