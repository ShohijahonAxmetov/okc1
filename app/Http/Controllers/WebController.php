<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Hash;
use DB;
use Image;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Banner;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Post;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Application;
use App\Models\Warehouse;
use App\Models\SpecialOfferClient;
use App\Http\Controllers\FargoController;
use App\Http\Controllers\ZoodpayController;

class WebController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->search;
        if (!$search) {
            return response(null);
        }
        $products = Product::where(DB::raw('JSON_EXTRACT(LOWER(title), "$.uz")'), 'like', '%' . $search . '%')
            ->orWhere(DB::raw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(`title`, '$.\"ru\"')))"), 'like', '%' . mb_strtolower($search) . '%')
            ->where('is_active', 1)
            ->latest()
            ->take(20)
            ->get();
        return response($products);
    }

    public function special_offer_client(Request $request)
    {
        $data = $request->all();
        if (SpecialOfferClient::where('email', $data['email'])->exists()) {
            return response(['message' => 'Vi uje podpisalis'], 400);
        }

        $validator = Validator($data, [
            'email' => 'required|max:255|email|unique:special_offer_clients'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        SpecialOfferClient::create($data);

        return response(['success' => true], 200);
    }

    public function application_store(Request $request)
    {
        $data = $request->all();

        $validator = Validator($data, [
            'name' => 'required',
            'phone_number' => 'required|max:255',
            'email' => 'max:255|nullable'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        Application::create($data);

        return response(['success' => true], 200);
    }

    public function comment_store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        $validator = Validator::make($data, [
            'product_id' => 'required|integer',
            'text' => 'required',
            'rating' => 'integer|required|in:1,2,3,4,5'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        Comment::create($data);
        $product = Product::find($data['product_id']);

        if (isset($data['rating']) && isset($product)) {
            $product->update([
                'rating' => round(((floatval($product->rating) * $product->number_of_ratings) + $data['rating']) / ($product->number_of_ratings + 1), 1),
                'number_of_ratings' => $product->number_of_ratings + 1
            ]);
        }

        return response(['message' => 'Kommentirovano', 'success' => true], 200);
    }

    public function get_comments($id)
    {
        $comments = Comment::where('product_id', $id)->with('user')->get();

        return response(['comments' => $comments, 'success' => true], 200);
    }

    public function get_user_orders()
    {
        if (auth()->check()) {
            $orders = auth()
                ->user()
                ->orders()
                ->with('productVariations', 'productVariations.product.brand', 'productVariations.productVariationImages')
                ->latest()
                ->get();

            return response([
                'data' => $orders
            ], 200);
        } else {
            return response(['message' => 'Net takogo polzovatelya'], 400);
        }
    }

    public function order(Request $request)
    {
        // esli vibran zabrat i magazina, to doljen i vibiratsya magazin
        if($request->with_delivery == 0) {
            $validator = Validator::make($request->all(), [
                'shop' => 'required'
            ]);
            if ($validator->fails()) {
                return response([
                    'message' => $validator->errors()
                ], 400);
            }
        }

        /* esli netu dostatochnogo kolichestva produktov v magazine */
        $check_product_sufficiency = $this->check_product_sufficiency($request->all());
        if(!$check_product_sufficiency) {
            return response([
                'message' => 'Нет такого кол-ва товаров'
            ], 400);
        }

        // poluchat ceni dostavki s Fargo
        $fargo_prices = new FargoController;
        $delivery_prices = $fargo_prices->get_prices();
        $delivery_price = $request->region == 1 ? $delivery_prices['city_base_price'] : $delivery_prices['door_door_base_price'];
        $free_delivery_from = 700000;

        // preobrazovanie kollekcii v massiv
        $data = $request->all();

        // validaciya dannix
        $validator = Validator::make($data, [
            'phone_number' => 'required|max:255',
            'with_delivery' => 'required',
            'payment_method' => 'required|in:cash,online,card',
            'payment_card' => 'nullable|in:payme,click,zoodpay',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        // podgotovka dannix dlya zapisi
        $data['product_variations'] = json_decode($data['product_variations'], true);
        $data['phone_number'] = preg_replace('/[^0-9]/', '', $data['phone_number']);
        $amount = 0;
        foreach ($data['product_variations'] as $variation) {
            $product_variation = ProductVariation::find($variation['id']);

            if (isset($product_variation->discount_price)) {
                $amount += (preg_replace('/[^0-9]/', '', $product_variation->discount_price) * 100) * $variation['count'];
            } else {
                $amount += (preg_replace('/[^0-9]/', '', $product_variation->price) * 100) * $variation['count'];
            }
        }

        // esli s dostavkoy pribavim summu dostavki
        if ($data['with_delivery'] == 1) {
            $amount += $delivery_price * 100;
        }
        if ($data['payment_method'] == 'cash') {

            $data['amount'] = $amount;

        } else {


            if ($data['payment_card'] == 'payme') {

                $data['amount'] = $amount;

            } else if ($data['payment_card'] == 'click') {

                $data['amount'] = $amount / 100;

            } else if ($data['payment_card'] == 'zoodpay') { // this changed

                $data['amount'] = $amount / 100;

            } else {

                return response([
                    'success' => false,
                    'message' => 'Payment system not found'
                ]);

            }


        }
        unset($amount);
        $data['status'] = 'new';

        // soxranenie dannix
        DB::beginTransaction();
        try {
            // ustanovit otkuda klient xochet brat tovari
            $data['shop'] = Warehouse::find($data['shop'])['integration_id'];

            //get warehouse_id for fargo
            $warehouse_id = Warehouse::where('for_fargo', true)
                ->first();

            if(!$warehouse_id) {
                return response([
                    'success' => false,
                    'message' => 'Не выбран склад для'
                ], 400);
            }

            $data['warehouse_id'] = $data['with_delivery'] == 1 ? $warehouse_id->integration_id : $data['shop'];

            $order = Order::create($data);

            foreach ($data['product_variations'] as $variation) {
                $product_variation = ProductVariation::find($variation['id']);

                // $brand_discount = $product_variation->product->brand->discount;
                // isset($brand_discount) ? $brand_discount = $brand_discount->discount : null;
                $brand_discount = null;

                $order->productVariations()->attach($variation['id'], [
                    'count' => $variation['count'],
                    'price' => $product_variation->price,
                    'discount_price' => $product_variation->discount_price,
                    'brand_discount' => $brand_discount
                ]);
            }

            // send message to telegram group with bot
            $total = 0;

            if ($data['payment_method'] == 'cash') {
                $total = $order->amount / 100;
            } else {
                if ($data['payment_card'] == 'payme') {
                    $total = $order->amount /100;
                } else if ($data['payment_card'] == 'click') {
                    $total = $order->amount;
                } else if ($data['payment_card'] == 'zoodpay') {
                    $total = $order->amount;
                }
            }

            file_get_contents('https://api.telegram.org/bot5575336376:AAGG1V_yLba7eT3H9WoVFvw_-bJE2Ll5oK8/sendMessage?parse_mode=HTML&chat_id=-1001746315950&text=' .
                'Новый заказ' .

                '%0A%0A<b>Заказ ID:</b> ' . $order->id .

                '%0A%0A<b>Имя:</b> ' . $order->name .
                "%0A<b>Номер телефона:</b> " . $order->phone_number .  // %2B - this is +

                '%0A%0A<b>Сумма:</b> ' . $total . ' сум' .

                "%0A%0A<a href='https://admin.okc.uz/dashboard/orders'>" . 'Перейти на сайт' . "</a>");

            // // send data to 1c
            $res = $this->order_to_venkom($order);

            if(!$res['success']) {
                return response([
                    'success' => false,
                    'message' => $res['error']
                ]);
            }

            DB::commit();

            if ($data['payment_method'] != 'cash') {

                if ($data['payment_card'] == 'click') {
                    return response([
                        'url' => url('') . '/api/pay/click/' . $order->id . '/' . ($order->amount),
                        'with_url' => true,
                        'success' => true
                    ]);
                } else if ($data['payment_card'] == 'payme') {
                    return response([
                        'url' => url('') . '/api/pay/payme/' . $order->id . '/' . ($order->amount / 100),
                        'with_url' => true,
                        'success' => true
                    ]);
                } else if ($data['payment_card'] == 'zoodpay') {

                    $zoodpay = new ZoodpayController;
                    $payment_url = $zoodpay->create_transaction($order);

                    return response([
                        'url' => $payment_url,
                        'with_url' => true,
                        'success' => true
                    ]);
                } else {
                    return response([
                        'success' => false,
                        'message' => 'Payment system not found'
                    ]);
                }
            } else {
                return response([
                    'with_url' => false,
                    'success' => true
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'message' => 'Error',
                'success' => false
            ], 400);
        }
    }

    public function banners()
    {
        $banners = Banner::where('is_active', 1)->latest()->get();
        return response(['data' => $banners], 200);
    }

    public function popular_products()
    {
        $popular_products = Product::whereHas('productVariations')
            ->where('is_popular', 1)
            ->where('is_active', 1)
            ->with('brand')
            ->latest()
            ->take(4)
            ->get();

        return response(['data' => $popular_products], 200);
    }

    public function new_products()
    {
        $new_products = Product::whereHas('productVariations')
            ->where('is_active', 1)
            ->latest()
            ->with('brand', 'categories', 'productVariations')
            ->take(8)
            ->get();
        return response(['data' => $new_products], 200);
    }

    public function posts()
    {
        $posts = Post::latest()->take(6)->get();
        return response(['data' => $posts], 200);
    }

    public function all_posts()
    {
        $posts = Post::latest()
            ->paginate(12);

        return response([
            'success' => true,
            'data' => $posts
        ], 200);
    }

    public function post($slug)
    {
        $post = Post::where('slug', $slug)
            ->first();

        $post->increment('views_count');

        $other_posts = Post::latest()
            ->get()
            ->except($post->id);

        return response([
            'success' => true,
            'data' => $post,
            'other_posts' => $other_posts
        ], 200);
    }

    public function brands()
    {
        $brands = Brand::latest()
            ->with('products', 'products.productVariations', 'products.productVariations.color', 'products.categories')
            // ->has('products', '>', 1)
            ->get();

        return response([
            'data' => '$brands'
        ], 200);
    }

    public function brands_all()
    {
        $brands = Brand::where('is_active', 1)
            ->has('products', '>', 0)
            ->whereHas('products', function($q) {
                $q->where('is_active', 1);
            })
            ->latest()
            ->get();

        return response([
            'data' => $brands
        ], 200);
    }

    public function brand($slug)
    {
        $brand = Brand::where('slug', $slug)->with('products')->get();
        return response(['data' => $brand], 200);
    }

    public function categories()
    {
        $categories = Category::where('is_active', 1)
            ->latest()
            ->doesntHave('parent')
            ->with('children')
            ->with('products', 'products.productVariations')
            ->get();
            
        return response(['data' => $categories], 200);
    }

    public function categories_all()
    {
        $categories = Category::where('is_active', 1)
            ->orderBy('position')
            ->has('parent', '<', 1)
            ->with('children')
            ->get();

        return response([
            'data' => $categories
        ], 200);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->with('children')->first();
        $brothers = optional($category->parent)->children();
        if (!isset($brothers)) return response(['data' => $category, 'brothers' => []], 200);
        // collection editing
        $brothers = $brothers->with('children')->get()->where('slug', '!=', $slug)->values();
        return response(['data' => $category, 'brothers' => $brothers], 200);
    }

    public function category_products($slug, Request $request)
    {
        isset($request->brand) ? $brand = $request->brand : $brand = Brand::pluck('integration_id')->toArray();
        $end_price = isset($request->end_price) ? $request->end_price : Category::where('slug', $slug)
            ->first()
            ->products()
            ->where('is_default', 1)
            ->join('product_variations', 'products.id', '=', 'product_variations.product_id')
            ->select('product_variations.price')
            ->max('price');
        $start_price = isset($request->start_price) ? $request->start_price : Category::where('slug', $slug)
            ->first()
            ->products()
            ->where('is_default', 1)
            ->join('product_variations', 'products.id', '=', 'product_variations.product_id')
            ->select('product_variations.price')
            ->min('price');

        if (!isset($request->sort)) {
            $products = Product::join('category_product', 'products.id', '=', 'category_product.product_id')
                ->join('categories', function ($join) use ($slug) {
                    $join->on('category_product.category_id', '=', 'categories.integration_id')
                        ->where('slug', '=', $slug);
                })
                ->join('product_variations', function ($join) use ($start_price, $end_price) {
                    $join->on('products.id', '=', 'product_variations.product_id')
                        ->where('product_variations.is_default', 1)
                        ->whereBetween('product_variations.price', [$start_price, $end_price]);
                })
                ->where('product_variations.is_active', 1)
                ->latest()
                ->select('products.*')
                ->whereIn('brand_id', $brand)
                ->with('brand')
                ->paginate(12);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        }

        $sort_type = $request->sort;
        if ($sort_type == 'popular') {
            $products = Product::join('category_product', 'products.id', '=', 'category_product.product_id')
                ->join('categories', function ($join) use ($slug) {
                    $join->on('category_product.category_id', '=', 'categories.integration_id')
                        ->where('slug', '=', $slug);
                })
                ->join('product_variations', function ($join) use ($start_price, $end_price) {
                    $join->on('products.id', '=', 'product_variations.product_id')
                        ->where('product_variations.is_default', 1)
                        ->whereBetween('product_variations.price', [$start_price, $end_price]);
                })
                ->orderBy('products.is_popular', 'desc')
                ->where('product_variations.is_active', 1)
                ->select('products.*')
                ->whereIn('brand_id', $brand)
                ->with('brand')
                ->paginate(12);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        } else if ($sort_type == 'expensive') {
            $products = Product::join('category_product', 'products.id', '=', 'category_product.product_id')
                ->join('categories', function ($join) use ($slug) {
                    $join->on('category_product.category_id', '=', 'categories.integration_id')
                        ->where('slug', '=', $slug);
                })
                ->join('product_variations', function ($join) use ($start_price, $end_price) {
                    $join->on('products.id', '=', 'product_variations.product_id')
                        ->where('product_variations.is_default', 1)
                        ->whereBetween('product_variations.price', [$start_price, $end_price]);
                })
                ->orderBy('product_variations.price', 'desc')
                ->where('product_variations.is_active', 1)
                ->select('products.*')
                ->whereIn('brand_id', $brand)
                ->with('brand')
                ->paginate(12);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        } else if ($sort_type == 'cheap') {
            $products = Product::join('category_product', 'products.id', '=', 'category_product.product_id')
                ->join('categories', function ($join) use ($slug) {
                    $join->on('category_product.category_id', '=', 'categories.integration_id')
                        ->where('slug', '=', $slug);
                })
                ->join('product_variations', function ($join) use ($start_price, $end_price) {
                    $join->on('products.id', '=', 'product_variations.product_id')
                        ->where('product_variations.is_default', 1)
                        ->whereBetween('product_variations.price', [$start_price, $end_price]);
                })
                ->orderBy('product_variations.price')
                ->where('product_variations.is_active', 1)
                ->select('products.*')
                ->whereIn('brand_id', $brand)
                ->with('brand')
                ->paginate(12);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        } else if ($sort_type == 'new') {
            $products = Product::join('category_product', 'products.id', '=', 'category_product.product_id')
                ->join('categories', function ($join) use ($slug) {
                    $join->on('category_product.category_id', '=', 'categories.integration_id')
                        ->where('slug', '=', $slug);
                })
                ->join('product_variations', function ($join) use ($start_price, $end_price) {
                    $join->on('products.id', '=', 'product_variations.product_id')
                        ->where('product_variations.is_default', 1)
                        ->whereBetween('product_variations.price', [$start_price, $end_price]);
                })
                ->latest()
                ->where('product_variations.is_active', 1)
                ->select('products.*')
                ->whereIn('brand_id', $brand)
                ->with('brand')
                ->paginate(12);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        } else {
            return response([
                'message' => 'Nesushestvuyushoe znachenie sortirovki'
            ], 400);
        }
    }

    public function product($slug, Request $request)
    {
        $product = ProductVariation::where('slug', $slug)
            ->with('productVariationImages', 'product', 'product.brand', 'product.categories')
            ->first();

        if (!$product) return response(['message' => 'Takogo produkta ne sushestvuyet', 'success' => false], 404);
        // vse variacii producks
        $variations = ProductVariation::where('slug', $slug)->with('productVariationImages')
            ->first()
            ->product
            ->productVariations()
            ->with('attributeOptions')
            ->with('attributeOptions.attribute')
            ->get();
        // vse attribute produkta
        $attributes = [];
        foreach ($variations as $variation) {
            foreach ($variation->attributeOptions as $option) {
                $attributes[] = $option->attribute;
            }
        }
        $attributes = array_unique($attributes);
        // current optioni produkta
        $current_attr_options = $product->attributeOptions;
        $current_attributes = [];
        foreach ($attributes as $attribute) {
            foreach ($current_attr_options as $option) {
                if ($attribute->id == $option->attribute->id) {
                    $current_attributes[$attribute->id] = $option->id;
                }
            }
        }

        if (isset($request->attribute) && isset($request->option)) {
            $attribute_id = $request->attribute;
            $option_id = $request->option;

            Arr::pull($current_attributes, 34);

            $product = ProductVariation::whereHas('attributeOptions', function ($query) use ($attribute_id, $option_id) {
                $query->where('attribute_id', $attribute_id)->where('attribute_option_id', $option_id);
            });
            foreach ($current_attributes as $key => $current_attribute) {
                $product = $product->whereHas('attributeOptions', function ($query) use ($key, $current_attribute) {
                    $query->where('attribute_id', $key)->where('attribute_option_id', $current_attribute);
                });
            }
            if (!is_null($product->first())) {
                $product = $product->with('productVariationImages', 'product', 'product.brand', 'product.categories')->first();
            } else {
                $product = ProductVariation::whereHas('attributeOptions', function ($query) use ($attribute_id, $option_id) {
                    $query->where('attribute_id', $attribute_id)
                        ->where('attribute_option_id', $option_id);
                })->with('productVariationImages', 'product', 'product.brand', 'product.categories')
                    ->first();
            }
        }

        $data_exists = Category::whereHas('products.productVariations', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->exists();

        if ($data_exists) {
            $similar_products = Category::whereHas('products.productVariations', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->first()->products()->where('is_active', 1)->with('productVariations', 'productVariations.productVariationImages')->take(8)->get()->except($product->product->id);
        } else {
            $similar_products = null;
        }


        try {
            // optioni response produkta
            $current_attr_options = $product->attributeOptions;
            $current_attributes = [];
            foreach ($attributes as $attribute) {
                foreach ($current_attr_options as $option) {
                    if ($attribute->id == $option->attribute->id) {
                        $current_attributes[$attribute->id] = $option->id;
                    }
                }
            }


            $result1 = [];
            foreach ($attributes as $attribute) { //rang, qoshimcha, hajm
                $result['attribute_id'] = $attribute->id;
                $result['attribute_title'] = $attribute->title;
                foreach ($variations as $variation) { // 4ta product variation
                    $values = [];
                    foreach ($variation->attributeOptions as $attribute_option) { // har variatinning optionlari
                        // var_dump($attribute_option->id);
                        if ($attribute->id == $attribute_option->attribute_id) {
                            $values['option_id'] = $attribute_option->id;
                            $values['option_key'] = $attribute_option->key;
                            $values['option_value'] = $attribute_option->value;
                            // dd($attribute_option->id);
                            if (isset($current_attributes[$attribute->id])) {
                                $values['selected'] = $current_attributes[$attribute->id] == $attribute_option->id ? true : false;
                            } else {
                                $values['selected'] = false;
                            }
                            if (isset($result['values'])) {
                                if ($result['values'][0]['option_id'] != $values['option_id']) {
                                    $result['values'][] = $values;
                                    $values = [];
                                }
                            } else {
                                $result['values'][] = $values;
                                $values = [];
                            }
                        }
                    }
                }
                $result1[] = $result;
                $result = [];
            }

            return response([
                'product' => $product,
                'attributes' => $result1,
                'similar_products' => $similar_products
            ], 200);
        } catch (\Exception $e) {

            return response([
                'message' => 'Net takogo produkta'
            ], 404);
        }
    }

    public function get_products_by_id(Request $request)
    {

        $products = [];
        // dd(json_decode($request->ids));
        foreach (json_decode($request->ids) as $id) {
            $product = ProductVariation::where('id', $id)->with('productVariationImages', 'product', 'product.brand', 'attributeOptions.attribute')->first();
            if (!isset($product)) return response(['message' => 'Takogo produkta ne sushestvuyet', 'success' => false], 404);
            $products[] = $product;
        }

        return response(['data' => $products], 200);
    }

    public function profile_update(Request $request)
    {
        $data = $request->all();

        if (!isset($data['password'])) {
            $data['password'] = auth()->user()->password;
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        // validaciya dannix
        $validator = Validator::make($data, [
            'phone_number' => 'required|max:255',
            Rule::unique('users')->ignore(auth()->user()->id),
            'img' => 'nullable|mimes:jpg,jpeg,png|max:3072'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $data['phone_number'] = preg_replace('/[^0-9]/', '', $data['phone_number']);
        $data['username'] = preg_replace('/[^0-9]/', '', $data['phone_number']);

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = Str::random(12) . '.' . $img->extension();
            $saved_img = $img->move(public_path('/upload/users'), $img_name);
            Image::make($saved_img)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path() . '/upload/users/200/' . $img_name, 60);
            $data['img'] = $img_name;
        }

        $user = auth()->user()->update($data);

        return response(['message' => 'Uspeshno obnovlen', 'success' => true], 200);
    }

    public function products(Request $request)
    {
        $brand = isset($request->brand) ? $request->brand : Brand::pluck('integration_id')->toArray();
        $end_price = isset($request->end_price) ? $request->end_price : ProductVariation::max('price');
        $start_price = isset($request->start_price) ? $request->start_price : ProductVariation::min('price');
        // v1.1
        $search = isset($request->search) ? $request->search : '';

        if (!isset($request->sort)) {
            $products = Product::whereIn('brand_id', $brand)
                ->join('product_variations', function ($join) use ($start_price, $end_price) {
                    $join->on('products.id', '=', 'product_variations.product_id')
                        ->where('product_variations.is_default', 1)
                        ->whereBetween('product_variations.price', [$start_price, $end_price]);
                })
                ->latest()
                ->select('products.*')
                // ->whereIn('brand_id', $brand)
                ->where(DB::raw('JSON_EXTRACT(LOWER(title), "$.uz")'), 'like', '%' . $search . '%')
                ->orWhere(DB::raw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(`title`, '$.\"ru\"')))"), 'like', '%' . mb_strtolower($search) . '%')
                ->where('products.is_active', 1)
                ->with('brand', 'categories')
                ->paginate(12);

            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        }

        $sort_type = $request->sort;
        if ($sort_type == 'popular') {
            $products = Product::join('product_variations', function ($join) use ($start_price, $end_price) {
                $join->on('products.id', '=', 'product_variations.product_id')
                    ->where('product_variations.is_default', 1)
                    ->whereBetween('product_variations.price', [$start_price, $end_price]);
                })
                ->orderBy('products.is_popular', 'desc')
                ->select('products.*')
                ->where('products.is_active', 1);

            if($search != '') {
                $products = $products->where(DB::raw('JSON_EXTRACT(LOWER(title), "$.uz")'), 'like', '%' . $search . '%')
                    ->orWhere(DB::raw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(`title`, '$.\"ru\"')))"), 'like', '%' . mb_strtolower($search) . '%');
            }

            $products = $products->whereIn('brand_id', $brand)
                ->where('is_popular', 1)
                ->with('brand')
                ->paginate(12);
                

            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
            
        } else if ($sort_type == 'expensive') {
            $products = Product::join('product_variations', function ($join) use ($start_price, $end_price) {
                $join->on('products.id', '=', 'product_variations.product_id')
                    ->where('product_variations.is_default', 1)
                    ->whereBetween('product_variations.price', [$start_price, $end_price]);
            })
                ->orderBy('product_variations.price', 'desc')
                ->select('products.*')
                ->whereIn('brand_id', $brand)
                ->where('products.is_active', 1)
                ->with('brand')
                ->paginate(12);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        } else if ($sort_type == 'cheap') {
            $products = Product::join('product_variations', function ($join) use ($start_price, $end_price) {
                $join->on('products.id', '=', 'product_variations.product_id')
                    ->where('product_variations.is_default', 1)
                    ->whereBetween('product_variations.price', [$start_price, $end_price]);
            })
                ->orderBy('product_variations.price')
                ->select('products.*')
                ->whereIn('brand_id', $brand)
                ->where('products.is_active', 1)
                ->with('brand')
                ->paginate(12);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        } else if ($sort_type == 'new') {
            $products = Product::join('product_variations', function ($join) use ($start_price, $end_price) {
                    $join->on('products.id', '=', 'product_variations.product_id')
                        ->where('product_variations.is_default', 1)
                        ->whereBetween('product_variations.price', [$start_price, $end_price]);
                })
                ->latest()
                ->select('products.*')
                ->where('products.is_active', 1);

            if($search != '') {
                $products = $products->where(DB::raw('JSON_EXTRACT(LOWER(title), "$.uz")'), 'like', '%' . $search . '%')
                    ->orWhere(DB::raw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(`title`, '$.\"ru\"')))"), 'like', '%' . mb_strtolower($search) . '%');
            }

            $products = $products->whereIn('brand_id', $brand)
                ->with('brand')
                ->paginate(12);
                
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        } else {
            return response([
                'message' => 'Nesushestvuyushoe znachenie sortirovki',
                'success' => false
            ], 400);
        }
    }

    public function store_to_allin()
    {
        $products = \App\Models\ProductVariation::with('product', 'product.brand', 'productVariationImages')
            ->where('remainder', '!=', 0)
            ->get();

        $data = [];
        $counter = 0;
        foreach ($products as $product) {
            $data[$counter]['id'] = $product->integration_id;
            $data[$counter]['title'] = $product->product->title['ru'] ?? null;
            $data[$counter]['desc'] = $product->product->desc['ru'] ?? null;
            $data[$counter]['how_to_use'] = $product->product->how_to_use['ru'] ?? null;
            $data[$counter]['brand'] = $product->product->brand->title ?? null;
            $data[$counter]['remainder'] = $product->remainder ?? null;

            $image_counter = 0;
            if (isset($product->productVariationImages[0])) {
                foreach ($product->productVariationImages as $img) {
                    $data[$counter]['images'][$image_counter] = $img->img;
                    $image_counter++;
                }
                $image_counter = 0;
                $data[$counter]['images'] = implode('|', $data[$counter]['images']);
            }
            $counter++;
        }

        $fp = fopen(public_path('allin.csv'), 'w');

        fputcsv($fp, ['ID', 'Title', 'Description', 'How to use', 'Brand', 'Remainder', 'Images']);

        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
    }

    public function get_regions_districts(Request $request)
    {
        $districts = array_values(array_filter(config('app.DISTRICTS'), function ($item) use ($request) {
            if ($item['parent_id'] == $request->region_id) return $item;
        }));

        return response(['success' => true, 'districts' => $districts]);
    }

    // upload image for CKEditor
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $fileName = time() . '.' . $request->file('upload')->getClientOriginalExtension();

            $request->file('upload')->move(public_path('upload'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('upload/' . $fileName);
            $msg = 'Image upload successfully!';

            return response([
                'uploaded' => 1,
                'fileName' => $fileName,
                'url' => $url
            ]);
            // $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            // @header('Content-type: text/html; charset=utf-8');
            // echo $response;
        }
    }

    public function order_to_venkom(Order $order) // Order $order
    {
        // $new_ip_address = '94.232.24.102';
        $new_ip_address = env('C_IP');
        $old_ip_address = '213.230.65.189';

        $base_url = 'http://' . $new_ip_address . '/UT_NewClean/hs/invema_API';
        $login = 'Venkon';
        $password = 'overlord';

        // $order = Order::find(42);
        // podgotovka products
        $order_products = [];
        $counter = 0;
        foreach($order->productVariations as $item) {
            $order_products[$counter]['product_id'] = $item->integration_id;
            $order_products[$counter]['count'] = $item->pivot->count;
            $order_products[$counter]['price'] = $item->price;
            $order_products[$counter]['discount'] = $item->discount_price ? $item->discount_price : 0;
            $order_products[$counter]['total'] = $order_products[$counter]['discount'] != 0 
                ? $order_products[$counter]['discount'] * $order_products[$counter]['count'] 
                : $order_products[$counter]['price'] * $order_products[$counter]['count'];
            $order_products[$counter]['warehouse_id'] = $order->warehouse_id;

            $counter ++;
        }
        unset($counter);

        // podgotovka total
        $total = 0;
        if ($order->payment_method == 'cash') {
            $total = $order->amount / 100;
        } else {
            if ($order->payment_card == 'payme') {
                $total = $order->amount /100;
            } else if ($order->payment_card == 'click') {
                $total = $order->amount;
            } else if ($order->payment_card == 'zoodpay') {
                $total = $order->amount;
            }
        }

        $req = [];

        $req['id'] = $order->id;
        $req['name'] = $order->name;
        $req['address'] = $order->address;
        $req['postal_code'] = $order->postal_code ?? '';
        $req['phone_number'] = $order->phone_number;
        $req['email'] = $order->email;
        $req['products'] = $order_products;
        $req['total'] = $total;
        $req['payment_method'] = $order->payment_method;
        $req['delivery_method'] = $order->delivery_method ?? '';
        $req['is_paid'] = $order->is_payed;
        $req['status'] = $order->status;
        $req['comments'] = $order->comments ?? '';

        // send order to venkom
        $res = Http::withBasicAuth($login, $password)
            ->post(
                $base_url.'/orders',
                $req
            );

        // dd($res->body());

        return $res;

        // if($res['success'] != true) {
        //     return [
        //         'success' => false,
        //         'message' => $res['error']
        //     ];
        // }

        // return [
        //     'success' => true
        // ];
    }

    public function get_warehouses(Request $request)
    {
        $data = $request->all();

        $all_warehouses = Warehouse::where('is_store', 1)
            ->has('productVariations', '>', 0)
            ->select('id', 'title','integration_id')
            ->get();

        $warehouses_for_res = [];

        foreach($all_warehouses as $warehouse) {
            $warehouse->active = false;
            $records_count = 0;
            foreach($data['products'] as $item) {
                $product_id = ProductVariation::find($item['product_id']);

                $record = DB::table('product_variation_warehouse')->where('product_variation_id', $product_id->integration_id)
                    ->where('warehouse_id', $warehouse->id)
                    ->where('remainder', '>=', $item['count'])
                    ->first();

                if($record) {
                    $records_count ++;
                }
            }

            if($records_count == count($data['products'])) {
                $warehouse->active = true;
            }
            $warehouses_for_res[] = $warehouse;
        }

        return response($warehouses_for_res);
    }

    public function check_product_sufficiency($data)
    {
        // json to array
        $data['product_variations'] = json_decode($data['product_variations'], true);

        $all_warehouses = Warehouse::where('is_store', 1)
            ->has('productVariations', '>', 0)
            ->select('id', 'title','integration_id')
            ->get();

        foreach($all_warehouses as $warehouse) {
            $warehouse->active = false;
            $records_count = 0;
            foreach($data['product_variations'] as $item) {
                $product_id = ProductVariation::find($item['id']);

                $record = DB::table('product_variation_warehouse')->where('product_variation_id', $product_id->integration_id)
                    ->where('warehouse_id', $warehouse->id)
                    ->where('remainder', '>=', $item['count'])
                    ->first();

                if($record) {
                    $records_count ++;
                }
            }

            if($records_count == count($data['product_variations'])) {
                $warehouse->active = true;
            }
            $warehouses_for_res[] = $warehouse;
        }

        foreach($warehouses_for_res as $item) {
            if($item['active'] == true) {
                return true;
            }
        }
        return false;
    }
}
