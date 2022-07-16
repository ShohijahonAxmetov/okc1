<?php

namespace App\Http\Controllers;

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
use App\Models\Discount;
use App\Models\Post;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Comment;
use App\Models\Application;
use App\Models\SpecialOfferClient;

class WebController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->search;
        if(!$search) {
            return response(null);
        }
        $products = Product::where(DB::raw('JSON_EXTRACT(LOWER(title), "$.uz")'), 'like', '%'.$search.'%')
                            ->orWhere(DB::raw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(`title`, '$.\"ru\"')))"), 'like', '%'.mb_strtolower($search).'%')
                            ->where('is_active', 1)
                            ->latest()
                            ->get();
        return response($products);
    }

    public function special_offer_client(Request $request)
    {
        $data = $request->all();
        if(SpecialOfferClient::where('email', $data['email'])->exists()) {
            return response(['message' => 'Vi uje podpisalis'], 400);
        }

        $validator = Validator($data, [
            'email' => 'required|max:255|email|unique:special_offer_clients'
        ]);
        if($validator->fails()) {
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
        if($validator->fails()) {
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
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        Comment::create($data);
        $product = Product::find($data['product_id']);

        if(isset($data['rating']) && isset($product)) {
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
        if(auth()->check()) {
            $orders = auth()->user()->orders()->with('productVariations', 'productVariations.product.brand', 'productVariations.productVariationImages')->get();
            return response(['data' => $orders], 200);
        } else {
            return response(['message' => 'Net takogo polzovatelya'], 400); 
        }
    }
    
    public function order(Request $request)
    {
    	$data = $request->all();

        // validaciya dannix
        $validator = Validator::make($data, [
            'phone_number' => 'required|max:255',
            'with_delivery' => 'required',
            'payment_method' => 'required|in:cash,online,card'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        // podgotovka dannix dlya zapisi
        $data['product_variations'] = json_decode($data['product_variations'], true);
        $data['phone_number'] = preg_replace('/[^0-9]/', '', $data['phone_number']);
        $amount = 0;
        foreach ($data['product_variations'] as $variation) {
            $product_variation = ProductVariation::find($variation['id']);
            // null t6girlanmasa wu joyini t6girlash kerak
            if(isset($product_variation->discount_price)) {
                $amount += (preg_replace('/[^0-9]/', '', $product_variation->discount_price) * 100) * $variation['count'];
            } else {
                $brand_discount = isset($product_variation->product->brand->discount) ? $product_variation->product->brand->discount : null;
                if($brand_discount) {
                    $amount += (100 - $brand_discount->discount)/100 * (preg_replace('/[^0-9]/', '', $product_variation->price) * 100) * $variation['count'];
                } else {
                    $amount += (preg_replace('/[^0-9]/', '', $product_variation->price) * 100) * $variation['count'];
                }
            }
        }
        $data['amount'] = $amount;
        unset($amount);
        $data['status'] = 'new';
        // soxranenie dannix
        DB::beginTransaction();
        try {
            $order = Order::create($data);
            foreach ($data['product_variations'] as $variation) {
                $product_variation = ProductVariation::find($variation['id']);

                $brand_discount = $product_variation->product->brand->discount;
                isset($brand_discount) ? $brand_discount = $brand_discount->discount : null;

                $order->productVariations()->attach($variation['id'],[
                    'count' => $variation['count'],
                    'price' => $product_variation->price,
                    'discount_price' => $product_variation->discount_price,
                    'brand_discount' => $brand_discount
                ]);
            }

            DB::commit();
            
            if($data['payment_method'] != 'cash') {
                return response([
                    // 'url' => url('').'/api/pay/payme/'.$order->id.'/'.($order->amount / 100),
                    'url' => url('').'/api/pay/click/'.$order->id.'/'.($order->amount / 100),
                    'with_url' => true,
                    'success' => true
                ]);
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

    public function banners() {
        $banners = Banner::where('is_active', 1)->latest()->get();
        return response(['data' => $banners], 200);
    }

    public function popular_products() {
        $popular_products = Product::whereHas('productVariations')
                                    ->where('is_popular', 1)
                                    ->where('is_active', 1)
                                    ->with('brand')
                                    ->latest()
                                    ->take(4)
                                    ->get();

        return response(['data' => $popular_products], 200);
    }

    public function new_products() {
        $new_products = Product::whereHas('productVariations')
                                ->where('is_active', 1)
                                ->latest()
                                ->with('brand', 'categories', 'productVariations')
                                ->take(8)
                                ->get();
        return response(['data' => $new_products], 200);
    }

    public function posts() {
        $posts = Post::latest()->take(6)->get();
        return response(['data' => $posts], 200);
    }

    public function all_posts()
    {
        $posts = Post::latest()
                        ->paginate(1);

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

    public function brands() {
        $brands = Brand::latest()->with('products', 'products.productVariations', 'products.productVariations.color', 'products.categories')->get();
        return response(['data' => $brands], 200);
    }
    
    public function brands_all()
    {
    	$brands = Brand::where('is_active', 1)->latest()->get();
    	return response(['data' => $brands], 200);
    }

    public function brand($slug) {
        $brand = Brand::where('slug', $slug)->with('products')->get();
        return response(['data' => $brand], 200);
    }

    public function categories() {
        $categories = Category::where('is_active', 1)->latest()->doesntHave('parent')->with('children')->with('products', 'products.productVariations')->get();
        return response(['data' => $categories], 200);
    }
    
    public function categories_all()
    {
    	$categories = Category::where('is_active', 1)->latest()->get();
        return response(['data' => $categories], 200);
    }

    public function category($slug) {
        $category = Category::where('slug', $slug)->with('children')->first();
        $brothers = optional($category->parent)->children();
        if(!isset($brothers)) return response(['data' => $category, 'brothers' => []], 200);
        // collection editing
        $brothers = $brothers->with('children')->get()->where('slug', '!=', $slug)->values();
        return response(['data' => $category, 'brothers' => $brothers], 200);
    }

    public function category_products($slug, Request $request) {
        isset($request->brand) ? $brand = $request->brand : $brand = Brand::pluck('venkon_id')->toArray();
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

        if(!isset($request->sort)) {
            $products = Product::join('category_product', 'products.id', '=', 'category_product.product_id')
                ->join('categories', function($join) use ($slug) {
                    $join->on('category_product.category_id', '=', 'categories.venkon_id')
                    ->where('slug', '=', $slug);
                })
                ->join('product_variations', function($join) use ($start_price, $end_price) {
                    $join->on('products.id', '=', 'product_variations.product_id')
                    ->where('product_variations.is_default', 1)
                    ->whereBetween('product_variations.price', [$start_price, $end_price]);
                })
                ->latest()
                ->select('products.*')
                ->whereIn('brand_id', $brand)
                ->with('brand')
                ->paginate(1);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        }

        $sort_type = $request->sort;
        if($sort_type == 'popular') {
            $products = Product::join('category_product', 'products.id', '=', 'category_product.product_id')
            ->join('categories', function($join) use ($slug) {
                $join->on('category_product.category_id', '=', 'categories.venkon_id')
                ->where('slug', '=', $slug);
            })
            ->join('product_variations', function($join) use ($start_price, $end_price) {
                $join->on('products.id', '=', 'product_variations.product_id')
                ->where('product_variations.is_default', 1)
                ->whereBetween('product_variations.price', [$start_price, $end_price]);
            })
            ->orderBy('products.is_popular', 'desc')
            ->select('products.*')
            ->whereIn('brand_id', $brand)
            ->with('brand')
            ->paginate(1);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);

        } else if($sort_type == 'expensive') {
            $products = Product::join('category_product', 'products.id', '=', 'category_product.product_id')
            ->join('categories', function($join) use ($slug) {
                $join->on('category_product.category_id', '=', 'categories.venkon_id')
                ->where('slug', '=', $slug);
            })
            ->join('product_variations', function($join) use ($start_price, $end_price) {
                $join->on('products.id', '=', 'product_variations.product_id')
                ->where('product_variations.is_default', 1)
                ->whereBetween('product_variations.price', [$start_price, $end_price]);
            })
            ->orderBy('product_variations.price', 'desc')
            ->select('products.*')
            ->whereIn('brand_id', $brand)
            ->with('brand')
            ->paginate(1);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        } else if($sort_type == 'cheap') {
            $products = Product::join('category_product', 'products.id', '=', 'category_product.product_id')
            ->join('categories', function($join) use ($slug) {
                $join->on('category_product.category_id', '=', 'categories.venkon_id')
                ->where('slug', '=', $slug);
            })
            ->join('product_variations', function($join) use ($start_price, $end_price) {
                $join->on('products.id', '=', 'product_variations.product_id')
                ->where('product_variations.is_default', 1)
                ->whereBetween('product_variations.price', [$start_price, $end_price]);
            })
            ->orderBy('product_variations.price')
            ->select('products.*')
            ->whereIn('brand_id', $brand)
            ->with('brand')
            ->paginate(1);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        } else if($sort_type == 'new') {
            $products = Product::join('category_product', 'products.id', '=', 'category_product.product_id')
            ->join('categories', function($join) use ($slug) {
                $join->on('category_product.category_id', '=', 'categories.venkon_id')
                ->where('slug', '=', $slug);
            })
            ->join('product_variations', function($join) use ($start_price, $end_price) {
                $join->on('products.id', '=', 'product_variations.product_id')
                ->where('product_variations.is_default', 1)
                ->whereBetween('product_variations.price', [$start_price, $end_price]);
            })
            ->latest()
            ->select('products.*')
            ->whereIn('brand_id', $brand)
            ->with('brand')
            ->paginate(1);
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

    public function product($slug, Request $request) {

        $product = ProductVariation::where('slug', $slug)
            ->with('productVariationImages', 'product', 'product.brand', 'product.categories')
            ->first();

        if(!$product) return response(['message' => 'Takogo produkta ne sushestvuyet', 'success' => false], 404);
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
        foreach($variations as $variation) {
            foreach($variation->attributeOptions as $option) {
                $attributes[] = $option->attribute;
            }
        }
        $attributes = array_unique($attributes);
        // current optioni produkta
        $current_attr_options = $product->attributeOptions;
        $current_attributes = [];
        foreach($attributes as $attribute) {
            foreach($current_attr_options as $option) {
                if($attribute->id == $option->attribute->id) {
                    $current_attributes[$attribute->id] = $option->id;
                }
                
            }
        }

        if(isset($request->attribute) && isset($request->option)) {
            $attribute_id = $request->attribute;
            $option_id = $request->option;

            Arr::pull($current_attributes, 34);

            $product = ProductVariation::whereHas('attributeOptions', function($query) use ($attribute_id, $option_id) {
                $query->where('attribute_id', $attribute_id)->where('attribute_option_id', $option_id);
            });
            foreach($current_attributes as $key => $current_attribute) {
                $product = $product->whereHas('attributeOptions', function($query) use ($key, $current_attribute) {
                    $query->where('attribute_id', $key)->where('attribute_option_id', $current_attribute);
                });
            }
            if(!is_null($product->first())) {
                $product = $product->with('productVariationImages', 'product', 'product.brand', 'product.categories')->first();
            } else {
                $product = ProductVariation::whereHas('attributeOptions', function($query) use ($attribute_id, $option_id) {
                    $query->where('attribute_id', $attribute_id)
                            ->where('attribute_option_id', $option_id);
                })->with('productVariationImages', 'product', 'product.brand', 'product.categories')
                    ->first();
            }
        }

        $data_exists = Category::whereHas('products.productVariations', function($query) use ($slug) {
            $query->where('slug', $slug);
        })->exists();

        if($data_exists) {
            $similar_products = Category::whereHas('products.productVariations', function($query) use ($slug) {
                $query->where('slug', $slug);
            })->first()->products()->where('is_active', 1)->with('productVariations', 'productVariations.productVariationImages')->take(8)->get()->except($product->product->id);
        } else {
            $similar_products = null;
        }

    
        try {
            // optioni response produkta
            $current_attr_options = $product->attributeOptions;
            $current_attributes = [];
            foreach($attributes as $attribute) {
                foreach($current_attr_options as $option) {
                    if($attribute->id == $option->attribute->id) {
                        $current_attributes[$attribute->id] = $option->id;
                    }
                    
                }
            }


            $result1 = [];
            foreach ($attributes as $attribute) { //rang, qoshimcha, hajm
                $result['attribute_id'] = $attribute->id;
                $result['attribute_title'] = $attribute->title;
                foreach($variations as $variation) { // 4ta product variation
                    $values = [];
                    foreach($variation->attributeOptions as $attribute_option) { // har variatinning optionlari
                        // var_dump($attribute_option->id);
                        if($attribute->id == $attribute_option->attribute_id) {
                            $values['option_id'] = $attribute_option->id;
                            $values['option_key'] = $attribute_option->key;
                            $values['option_value'] = $attribute_option->value;
                            // dd($attribute_option->id);
                            if(isset($current_attributes[$attribute->id])) {
                                $values['selected'] = $current_attributes[$attribute->id] == $attribute_option->id ? true : false;
                            } else {
                                $values['selected'] = false;
                            }
                            if(isset($result['values'])) {
                                if($result['values'][0]['option_id'] != $values['option_id']) {
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

        } catch(\Exception $e) {

            return response([
                'message' => 'Net takogo produkta'
            ], 404);
        }
    }

    public function get_products_by_id(Request $request) {

        $products = [];
        // dd(json_decode($request->ids));
        foreach (json_decode($request->ids) as $id) {
            $product = ProductVariation::where('id', $id)->with('productVariationImages', 'product', 'product.brand', 'attributeOptions.attribute')->first();
            if(!isset($product)) return response(['message' => 'Takogo produkta ne sushestvuyet', 'success' => false], 404);
            $products[] = $product;
        }

        return response(['data' => $products], 200);
    }

    public function profile_update(Request $request) {
        $data = $request->all();

        if(!isset($data['password'])) {
            $data['password'] = auth()->user()->password;
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        // validaciya dannix
        $validator = Validator::make($data, [
            'phone_number' => 'required|max:255',
            Rule::unique('users')->ignore(auth()->user()->id),
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $data['phone_number'] = preg_replace('/[^0-9]/', '', $data['phone_number']);
        $data['username'] = preg_replace('/[^0-9]/', '', $data['phone_number']);

        if($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = Str::random(12).'.'.$img->extension();
            $saved_img = $img->move(public_path('/upload/users'), $img_name);
            Image::make($saved_img)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path().'/upload/users/200/'.$img_name, 60);
            $data['img'] = $img_name;
        }

        $user = auth()->user()->update($data);

        return response(['message' => 'Uspeshno obnovlen', 'success' => true], 200);
    }

    public function products(Request $request) {
        $brand = isset($request->brand) ? $request->brand : Brand::pluck('venkon_id')->toArray();
        $end_price = isset($request->end_price) ? $request->end_price : ProductVariation::max('price');
        $start_price = isset($request->start_price) ? $request->start_price : ProductVariation::min('price');
        // v1.1
        $search = isset($request->search) ? $request->search : '';

        if(!isset($request->sort)) {
            $products = Product::whereIn('brand_id', $brand)
                                ->join('product_variations', function($join) use ($start_price, $end_price) {
                                    $join->on('products.id', '=', 'product_variations.product_id')
                                    ->where('product_variations.is_default', 1)
                                    ->whereBetween('product_variations.price', [$start_price, $end_price]);
                                })
                                ->latest()
                                ->select('products.*')
                                // ->whereIn('brand_id', $brand)
                                ->where(DB::raw('JSON_EXTRACT(LOWER(title), "$.uz")'), 'like', '%'.$search.'%')
                                ->orWhere(DB::raw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(`title`, '$.\"ru\"')))"), 'like', '%'.mb_strtolower($search).'%')
                                ->where('products.is_active', 1)
                                ->with('brand', 'categories')
                                ->paginate(1);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);

            // $products = Product::
            // where(DB::raw('JSON_EXTRACT(LOWER(title), "$.uz")'), 'like', '%'.$search.'%')
            // ->orWhere(DB::raw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(`title`, '$.\"ru\"')))"), 'like', '%'.mb_strtolower($search).'%')
            // ->latest()
            // ->get();
        }

        $sort_type = $request->sort;
        if($sort_type == 'popular') {
            $products = Product::join('product_variations', function($join) use ($start_price, $end_price) {
                                    $join->on('products.id', '=', 'product_variations.product_id')
                                    ->where('product_variations.is_default', 1)
                                    ->whereBetween('product_variations.price', [$start_price, $end_price]);
                                })
                                ->orderBy('products.is_popular', 'desc')
                                ->select('products.*')
                                ->whereIn('brand_id', $brand)
                                ->where('is_popular', 1)
                                ->where('products.is_active', 1)
                                ->with('brand')
                                ->paginate(1);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);

        } else if($sort_type == 'expensive') {
            $products = Product::join('product_variations', function($join) use ($start_price, $end_price) {
                                    $join->on('products.id', '=', 'product_variations.product_id')
                                    ->where('product_variations.is_default', 1)
                                    ->whereBetween('product_variations.price', [$start_price, $end_price]);
                                })
                                ->orderBy('product_variations.price', 'desc')
                                ->select('products.*')
                                ->whereIn('brand_id', $brand)
                                ->where('products.is_active', 1)
                                ->with('brand')
                                ->paginate(1);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        } else if($sort_type == 'cheap') {
            $products = Product::join('product_variations', function($join) use ($start_price, $end_price) {
                                    $join->on('products.id', '=', 'product_variations.product_id')
                                    ->where('product_variations.is_default', 1)
                                    ->whereBetween('product_variations.price', [$start_price, $end_price]);
                                })
                                ->orderBy('product_variations.price')
                                ->select('products.*')
                                ->whereIn('brand_id', $brand)
                                ->where('products.is_active', 1)
                                ->with('brand')
                                ->paginate(1);
            return response([
                'data' => $products,
                'start_price' => $start_price,
                'end_price' => $end_price
            ], 200);
        } else if($sort_type == 'new') {
            $products = Product::join('product_variations', function($join) use ($start_price, $end_price) {
                                    $join->on('products.id', '=', 'product_variations.product_id')
                                    ->where('product_variations.is_default', 1)
                                    ->whereBetween('product_variations.price', [$start_price, $end_price]);
                                })
                                ->latest()
                                ->select('products.*')
                                ->whereIn('brand_id', $brand)
                                ->where('products.is_active', 1)
                                ->with('brand')
                                ->paginate(1);
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
		$products = \App\Models\ProductVariation::with('product', 'product.brand', 'productVariationImages')->get();
		
		$data = [];
		$counter = 0;
		foreach($products as $product) {
			$data[$counter]['id'] = $product->venkon_id;
			$data[$counter]['title'] = $product->product->title['ru'] ?? null;
			$data[$counter]['desc'] = $product->product->desc['ru'] ?? null;
			$data[$counter]['how_to_use'] = $product->product->how_to_use['ru'] ?? null;
			$data[$counter]['brand'] = $product->product->brand->title ?? null;
            $data[$counter]['remainder'] = $product->remainder ?? null;
			
			$image_counter = 0;
			if(isset($product->productVariationImages[0])) {
				foreach($product->productVariationImages as $img) {
					$data[$counter]['images'][$image_counter] = $img->img;
					$image_counter++;	
				}
				$image_counter = 0;
				$data[$counter]['images'] = implode('|' ,$data[$counter]['images']);
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
        $districts = array_values(array_filter(config('app.DISTRICTS'), function($item) use ($request) {
            if($item['parent_id'] == $request->region_id) return $item;
        }));

        return response(['success' => true, 'districts' => $districts]);
    }
}
