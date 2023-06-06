<?php

use App\Http\Controllers\{
	web\PageController,
	AuthController,
	AdminAuthController,
	AdminController,
	BrandController,
	FilterController,
	CategoryController,
	ProductController,
	ProductVariationImageController,
	AttributeController,
	WebController,
	UserController,
	DiscountController,
	CommentController,
	SpecialOfferClientController,
	VenkonController,
	WarehouseController,
	FargoController,
	ZoodpayController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware(['venkon_auth'])->group(function () {
	Route::prefix('brands')->group(function () {
		Route::post('', [VenkonController::class, 'brands_create']);
		Route::put('{id}', [VenkonController::class, 'brands_put']);
		Route::delete('{id}', [VenkonController::class, 'brands_delete']);
	});
	Route::prefix('categories')->group(function () {
		Route::post('', [VenkonController::class, 'categories_create']);
		Route::put('{id}', [VenkonController::class, 'categories_put']);
		Route::delete('{id}', [VenkonController::class, 'categories_delete']);
	});
	Route::prefix('colors')->group(function () {
		Route::post('', [VenkonController::class, 'colors_create']);
		Route::put('{id}', [VenkonController::class, 'colors_put']);
		Route::delete('{id}', [VenkonController::class, 'colors_delete']);
	});
	Route::prefix('products')->group(function () {
		Route::post('', [VenkonController::class, 'products_create']);
		Route::put('{id}', [VenkonController::class, 'products_put']);
		Route::delete('{id}', [VenkonController::class, 'products_delete']);
	});
	Route::prefix('warehouses')->group(function () {
		Route::post('', [VenkonController::class, 'warehouses_create']);
		Route::put('{id}', [VenkonController::class, 'warehouses_put']);
		Route::delete('{id}', [VenkonController::class, 'warehouses_delete']);
	});
	Route::prefix('discounts')->group(function () {
		Route::post('', [VenkonController::class, 'discounts_create']);
		Route::put('{id}', [VenkonController::class, 'discounts_put']);
		Route::delete('{id}', [VenkonController::class, 'discounts_delete']);
	});

	Route::put('orders/{id}', [VenkonController::class, 'orders_put']);

	Route::post('update_products_count', [VenkonController::class, 'update_products_count']);
});

Route::prefix('auth')->group(function() {
	Route::post('login', [AuthController::class, 'login']);
	Route::post('send_code', [AuthController::class, 'send_code']);
	Route::post('check_code', [AuthController::class, 'check_code']);
	Route::post('register', [AuthController::class, 'register']);
	Route::post('store', [AuthController::class, 'store']);
	Route::post('forgot', [AuthController::class, 'forgot']);
	Route::post('forgot_check_code', [AuthController::class, 'forgot_check_code']);
	Route::post('forgot_update', [AuthController::class, 'forgot_update']);
	Route::middleware('auth:api')->group(function() {
		Route::post('logout', [AuthController::class, 'logout']);
		Route::post('refresh', [AuthController::class, 'refresh']);
    	Route::get('me', [AuthController::class, 'me']);
    	// orders
    	// Route::apiResource('/orders', OrderController::class);
	});
});

Route::middleware('auth')->group(function() {
	Route::post('profile/update', [WebController::class, 'profile_update']);
	// Route::apiResource('/users', UserController::class);
	Route::post('users/{id}/delete-img', [UserController::class, 'delete_img']);
	// orders
    Route::get('get_user_orders', [WebController::class, 'get_user_orders']);
    Route::post('comment-store', [WebController::class, 'comment_store']);
});

Route::post('/order', [WebController::class, 'order']);
// for web page
Route::get('/banners', [WebController::class, 'banners']);
Route::get('/popular_products', [WebController::class, 'popular_products']);
Route::get('/new_products', [WebController::class, 'new_products']);
Route::get('/posts', [WebController::class, 'posts']);
Route::get('/all_posts', [WebController::class, 'all_posts']);
Route::get('/posts/{slug}', [WebController::class, 'post']);
Route::get('/brands', [WebController::class, 'brands']);
Route::get('/brands/all', [WebController::class, 'brands_all']);
Route::get('/brands/{slug}', [WebController::class, 'brand']);
Route::get('/categories', [WebController::class, 'categories']);
Route::get('/categories/all', [WebController::class, 'categories_all']);
Route::get('/categories/{slug}', [WebController::class, 'category']);
Route::get('/categories/{slug}/products', [WebController::class, 'category_products']);
Route::get('/products/{slug}', [WebController::class, 'product']);
Route::post('/get_products_by_id', [WebController::class, 'get_products_by_id']);
Route::get('/products', [WebController::class, 'products']);
Route::get('/search', [WebController::class, 'search']);
Route::post('/get_warehouses', [WebController::class, 'get_warehouses']);
Route::post('/application-store', [WebController::class, 'application_store']);
Route::post('/special-offer-client', [WebController::class, 'special_offer_client']);

// NEW ROUTES(06.06.23)
Route::get('pages', [PageController::class, 'get']);

Route::get('get_comments/{id}', [WebController::class, 'get_comments']);

Route::get('/zoodpay/configuration', [ZoodpayController::class, 'configuration']);

// for test
Route::get('discounts', function() {
	$discounts = \App\Models\Discount::all();
	return response(['discounts' => $discounts], 200);
});
Route::get('warehouses', function() {
	$warehouses = \App\Models\Warehouse::with('productVariations')->get();
	return response(['warehouses' => $warehouses], 200);
});
Route::get('colors', function() {
	$colors = \App\Models\Color::all();
	return response(['colors' => $colors], 200);
});




Route::get('/regions', function() {
	$regions = config('app.REGIONS');
	$districts = config('app.DISTRICTS');
	return response(['regions' => $regions, 'districts' => $districts], 200);
});

Route::prefix('admin')->group(function() {
	Route::post('login', [AdminAuthController::class, 'login']);
	Route::middleware('admin_check')->group(function() {
		Route::post('logout', [AdminAuthController::class, 'logout']);
		Route::post('refresh', [AdminAuthController::class, 'refresh']);
		Route::get('me', [AdminAuthController::class, 'me']);
		// users
		// Route::apiResource('/users', AdminController::class);
		// Route::post('users/{id}/delete-img', [AdminController::class, 'delete_img']);
		// edited to
		// Route::apiResource('/admins', AdminController::class);
		// Route::post('admins/{id}/delete-img', [AdminController::class, 'delete_img']);
		// filters
		Route::get('filters/all', [FilterController::class, 'all']);
		Route::apiResource('/filters', FilterController::class);
		// categories
		Route::get('categories/all', [CategoryController::class, 'all']);
		// Route::apiResource('/categories', CategoryController::class);
		Route::post('categories/{id}/delete-img', [CategoryController::class, 'delete_img']);
		// products
		Route::post('products/filter', [ProductController::class, 'filter']);
		// Route::apiResource('/products', ProductController::class);
		Route::apiResource('/product_variation_images', ProductVariationImageController::class);
		// attributes
		Route::get('attributes/all', [AttributeController::class, 'all']);
		Route::post('attributes/{id}/disable', [AttributeController::class, 'disable']);
		Route::apiResource('/attributes', AttributeController::class);
		// orders
		// Route::apiResource('/orders', OrderController::class);
		// banners
		// Route::apiResource('/banners', BannerController::class);
		// posts
		// Route::apiResource('/posts', PostController::class);
		// clients
		// Route::apiResource('/users', UserController::class);
		Route::post('users/{id}/delete-img', [UserController::class, 'delete_img']);
		// discounts
		Route::get('discounts/all', [DiscountController::class, 'all']);
		// Route::apiResource('/discounts', DiscountController::class);
		// applcations
		// Route::apiResource('/applications', ApplicationController::class);
		// special offer clients
		Route::post('special_offer_clients/send', [SpecialOfferClientController::class, 'send']);
		Route::get('special_offer_clients/all', [SpecialOfferClientController::class, 'all']);
		Route::apiResource('/special_offer_clients', SpecialOfferClientController::class);
		// warehouses
		Route::get('warehouses/all', [WarehouseController::class, 'all']);
		// Route::apiResource('/warehouses', WarehouseController::class);
		// colors
		Route::get('colors', [WebController::class, 'colors']);
	});
});

Route::middleware('admin_check')->get('test',function() {
    return response(['message' => 'Message for test )']);
});


//handle requests from payment system
Route::any('/handle/{paysys}',function($paysys){
    (new Goodoneuz\PayUz\PayUz)->driver($paysys)->handle();
});

//redirect to payment system or payment form
Route::any('/pay/{paysys}/{key}/{amount}',function($paysys, $key, $amount){
	$model = Goodoneuz\PayUz\Services\PaymentService::convertKeyToModel($key);
    $url = request('redirect_url','https://okc.uz/?alert=success'); // redirect url after payment completed
    $pay_uz = new Goodoneuz\PayUz\PayUz;
    $pay_uz
    	->driver($paysys)
    	->redirect($model, $amount, 860, $url);
});


Route::get('get_delivery_prices', [FargoController::class, 'get_prices']);