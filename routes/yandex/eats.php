<?php

use Illuminate\Support\Facades\Route;

Route::post('security/oauth/token', [\App\Http\Controllers\Yandex\YandexEatsController::class, 'getToken']);
Route::get('/nomenclature/{placeId}/prices', [\App\Http\Controllers\Yandex\YandexEatsController::class, 'prices']);
// Route::get('/place-info/v1/details', [\App\Http\Controllers\Yandex\YandexEatsController::class, 'places']);