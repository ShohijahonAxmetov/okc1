<?php

use Illuminate\Support\Facades\Route;


// routes for test venkon API
Route::get('express24', [\App\Http\Controllers\Express24Controller::class, 'categories  ']);

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

