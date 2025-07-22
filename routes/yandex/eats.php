<?php

use Illuminate\Support\Facades\Route;

Route::post('security/oauth/token', [\App\Http\Controllers\Yandex\YandexEatsController::class, 'getToken']);
Route::middleware('check_yandex_eats')->group(function() {
	Route::get('/nomenclature/{placeId}/prices', [\App\Http\Controllers\Yandex\YandexEatsController::class, 'prices']);
	Route::get('/nomenclature/composition', [\App\Http\Controllers\Yandex\YandexEatsController::class, 'composition']);
	Route::get('/nomenclature/{placeId}/availability', [\App\Http\Controllers\Yandex\YandexEatsController::class, 'availability']);
	Route::post('/order', [\App\Http\Controllers\Yandex\YandexEatsController::class, 'order']);
	Route::get('/order/{orderId}', [\App\Http\Controllers\Yandex\YandexEatsController::class, 'getOrder']);
	Route::get('/order/{orderId}/status', [\App\Http\Controllers\Yandex\YandexEatsController::class, 'getOrderStatus']);
	Route::put('/order/{orderId}/status', [\App\Http\Controllers\Yandex\YandexEatsController::class, 'updateOrderStatus']);
});