<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationImage;
use App\Models\Color;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()
            ->with('categories', 'brand', 'productVariations', 'productVariations.productVariationImages');

        if(isset($_GET['search'])) {
            $products->where('title', 'like', '%'.trim($_GET['search']).'%')
                ->orWhere('id', trim($_GET['search']));
        }
        $products = $products->paginate(12);

        $search = $_GET['search'] ?? '';

        return view('app.products.index', compact('products', 'search'));
        // return response(['data' => $products], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['product_variations'] = json_decode($data['product_variations'], true);

        $validator = Validator::make($data, [
            'brand_id' => 'required|integer',
            'title' => 'required|max:255',
            'is_active' => 'required|boolean',
            'categories' => 'required',
            'product_variations.*.price' => 'required',
            'product_variations.*.product_code' => 'required',
            'is_popular' => 'required|boolean'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            $data['title'] = json_decode($data['title']);
            $data['desc'] = json_decode($data['desc']);
            $data['how_to_use'] = json_decode("['uz' => '', 'ru' => '']");
            $data['meta_keywords'] = json_decode($data['meta_keywords']);
            $data['categories'] = json_decode($data['categories']);

            $product = Product::create($data);

            foreach ($data['product_variations'] as $item) {

                $item['product_id'] = $product->id;
                $item['price'] = preg_replace('/[^0-9]/', '', $item['price']);
                (isset($item['discount_price']) && $item['discount_price'] != '') ? $item['discount_price'] = preg_replace('/[^0-9]/', '', $item['discount_price']) : $item['discount_price'] = null;

                $item['slug'] = Str::slug($product->title['ru'] . '-' . $item['product_code'], '-');

                $product_variation = ProductVariation::create($item);

                if ($request->hasFile($item['for_image'])) {
                    foreach ($request->file($item['for_image']) as $img) {
                        $img_name = Str::random(12) . '.' . $img->extension();
                        $saved_img = $img->move(public_path('/upload/products'), $img_name);
                        Image::make($saved_img)->resize(200, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save(public_path() . '/upload/products/200/' . $img_name, 60);
                        Image::make($saved_img)->resize(600, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save(public_path() . '/upload/products/600/' . $img_name, 80);
                        $data['product_variation_id'] = $product_variation->id;
                        $data['img'] = $img_name;

                        ProductVariationImage::create($data);
                    }
                }

                if ($product_variation) {
                    $product_variation->attributeOptions()->sync($item['attributes']);

                    $result = '';
                    foreach ($product_variation->attributeOptions()->pluck('key')->toArray() as $item) {
                        $result .= '-' . $item['ru'];
                    }

                    is_null(optional($product_variation->attributeOptions)->first()) ? $item['slug'] = $product_variation->slug : $item['slug'] = $product_variation->slug . $result;
                    $item['slug'] = Str::slug($item['slug'], '-');
                    $product_variation->update($item);
                }
            }

            if ($product) {
                $product->categories()->sync($data['categories']);
            }

            DB::commit();

            return response(['message' => 'Успешно добавлен'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw ($e);
            return response(['message' => 'Ошибка'], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('categories', 'brand', 'productVariations', 'productVariations.productVariationImages', 'productVariations.attributeOptions')->find($id);
        return response(['data' => $product], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $data = $request->all();
    //     $data['product_variations'] = json_decode($data['product_variations'], true);

    //     $validator = Validator::make($data, [
    //         'brand_id' => 'required',
    //         'title' => 'required|max:255',
    //         'is_active' => 'required|boolean',
    //         'is_popular' => 'required|boolean',
    //         'categories' => 'required',
    //         'product_variations.*.price' => 'required',
    //         // 'product_variations.*.product_code' => 'required'
    //     ]);
    //     if($validator->fails()) {
    //         return response(['message' => $validator->errors()], 400);
    //     }

    //     DB::beginTransaction();

    //     try {
    //         $data['title'] = json_decode($data['title']);
    //         $data['desc'] = json_decode($data['desc']);
    //         $data['how_to_use'] = json_decode("['uz' => '', 'ru' => '']");
    //         $data['meta_keywords'] = json_decode($data['meta_keywords']);
    //         $data['categories'] = json_decode($data['categories']);

    //         $product = Product::find($id)->update($data);
    //         $after_update = Product::find($id);

    //         $item_ids = [];
    //         foreach($data['product_variations'] as $item) {

    //             $item['product_id'] = $after_update->id;
    //             $item['price'] = preg_replace('/[^0-9]/', '', $item['price']);
    //             isset($item['discount_price'])  && $item['discount_price'] != '' ? $item['discount_price'] = preg_replace('/[^0-9]/', '', $item['discount_price']) : $item['discount_price'] = null;

    //             if(isset($item['product_code'])) {
    //                 $item['slug'] = Str::slug($after_update->title['ru'].'-'.$item['product_code'], '-');
    //             } else {
    //                 $item['slug'] = Str::slug($after_update->title['ru'], '-');
    //             }

    //             if(isset($item['id'])) {
    //                 $product_variation = ProductVariation::find($item['id'])->update($item);
    //                 $product_variation = ProductVariation::find($item['id']);
    //                 $item_ids[] = $item['id']; 
    //             } else {
    //                 $product_variation = ProductVariation::create($item);
    //                 $item_ids[] = $product_variation->id;
    //             }

    //             if($request->hasFile($item['for_image'])) {
    //                 foreach ($request->file($item['for_image']) as $img) {
    //                     $img_name = Str::random(12).'.'.$img->extension();
    //                     $saved_img = $img->move(public_path('/upload/products'), $img_name);
    //                     Image::make($saved_img)->resize(200, null, function ($constraint) {
    //                         $constraint->aspectRatio();
    //                     })->save(public_path().'/upload/products/200/'.$img_name, 60);
    //                     Image::make($saved_img)->resize(600, null, function ($constraint) {
    //                         $constraint->aspectRatio();
    //                     })->save(public_path().'/upload/products/600/'.$img_name, 80);
    //                     $data['product_variation_id'] = $product_variation->id;
    //                     $data['img'] = $img_name;

    //                     ProductVariationImage::create($data);
    //                 }
    //             }

    //             if ($product_variation) {
    //                 $product_variation->attributeOptions()->sync($item['attributes']);

    //                 $result = '';
    //                 foreach ($product_variation->attributeOptions()->pluck('key')->toArray() as $item) {
    //                     $result .= '-'.$item['ru'];
    //                 }

    //                 is_null(optional($product_variation->attributeOptions)->first()) ? $item['slug'] = $product_variation->slug : $item['slug'] = $product_variation->slug.'-'.$result;
    //                 $item['slug'] = Str::slug($item['slug'], '-');
    //                 $product_variation->update($item);
    //             }
    //         }

    //         // delete option from attribute
    //         $after_update->productVariations()->whereNotIn('id', $item_ids)->delete();

    //         if ($product) {
    //             $after_update->categories()->sync($data['categories']);
    //         }

    //         DB::commit();

    //         return response(['message' => 'Успешно редактирован'], 200);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         throw($e);
    //         return response(['message' => 'Ошибка'], 400);
    //     }
    // }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'brand' => 'required',
            'categories' => 'required',
            'is_active' => 'required|boolean',
            'is_popular' => 'required|boolean',
            'variations_data' => 'required',
        ]);

        DB::beginTransaction();
        try {

            // podgotovka dannix dlya zapisi
            $data['title'] = [
                'ru' => $request->title_ru,
                'uz' => $request->title_uz
            ];
            $data['desc'] = [
                'ru' => $request->desc_ru,
                'uz' => $request->desc_uz
            ];
            $data['how_to_use'] = [
                'ru' => $request->how_to_use_ru,
                'uz' => $request->how_to_use_uz
            ];
            $data['meta_keywords'] = [
                'ru' => $request->meta_keywords_ru,
                'uz' => $request->meta_keywords_uz
            ];


            $product = Product::find($id)->update([
                'brand_id' => $request->brand,
                'title' => $data['title'],
                'desc' => $data['desc'],
                'how_to_use' => $data['how_to_use'],
                'meta_keywords' => $data['meta_keywords'],
                'is_popular' => $request->is_popular,
                'is_active' => $request->status
            ]);

            $product_categories = explode(',', $data['categories']);
            if (isset($product_categories[0])) {
                DB::table('category_product')->where('product_id', $id)->delete();
                foreach ($product_categories as $category_id) {
                    DB::table('category_product')->insert([
                        'category_id' => $category_id,
                        'product_id' => $id
                    ]);
                }
            }

            $is_first = 1;
            foreach(json_decode($request->variations_data) as $variation) {
                ProductVariation::find($variation[4]->value)->update([
                    'product_code' => $variation[0]->value,
                    'price' => $variation[1]->value,
                    'is_active' => $variation[2]->value,
                    'color_id' => $variation[3]->value,
                    'is_default' => $is_first
                ]);

                ProductVariation::find($variation[4]->value)->productVariationImages()->delete();
                $is_first = 0;
            }
            unset($is_first);


            foreach(json_decode($request->dropzone_images) as $image) {
                ProductVariationImage::create([
                    'product_variation_id' => $image->id,
                    'img' => $image->value
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response(['errors' => $e, 'message' => 'Error transaction', 'success' => false], 500);
        }

        return response(['success' => true], 200);


// // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 


        $data = $request->all();
        $data['product_variations'] = json_decode($data['product_variations'], true);

        $validator = Validator::make($data, [
            'brand_id' => 'required',
            'title' => 'required|max:255',
            'is_active' => 'required|boolean',
            'is_popular' => 'required|boolean',
            'categories' => 'required',
            'product_variations.*.price' => 'required',
            // 'product_variations.*.product_code' => 'required'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            $data['title'] = json_decode($data['title']);
            $data['desc'] = json_decode($data['desc']);
            $data['how_to_use'] = json_decode("['uz' => '', 'ru' => '']");
            $data['meta_keywords'] = json_decode($data['meta_keywords']);
            $data['categories'] = json_decode($data['categories']);

            $product = Product::find($id)->update($data);
            $after_update = Product::find($id);

            $item_ids = [];
            foreach ($data['product_variations'] as $item) {

                $item['product_id'] = $after_update->id;
                $item['price'] = preg_replace('/[^0-9]/', '', $item['price']);
                isset($item['discount_price'])  && $item['discount_price'] != '' ? $item['discount_price'] = preg_replace('/[^0-9]/', '', $item['discount_price']) : $item['discount_price'] = null;

                if (isset($item['product_code'])) {
                    $item['slug'] = Str::slug($after_update->title['ru'] . '-' . $item['product_code'], '-');
                } else {
                    $item['slug'] = Str::slug($after_update->title['ru'], '-');
                }

                if (isset($item['id'])) {
                    $product_variation = ProductVariation::find($item['id'])->update($item);
                    $product_variation = ProductVariation::find($item['id']);
                    $item_ids[] = $item['id'];
                } else {
                    $product_variation = ProductVariation::create($item);
                    $item_ids[] = $product_variation->id;
                }

                if ($request->hasFile($item['for_image'])) {
                    foreach ($request->file($item['for_image']) as $img) {
                        $img_name = Str::random(12) . '.' . $img->extension();
                        $saved_img = $img->move(public_path('/upload/products'), $img_name);
                        Image::make($saved_img)->resize(200, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save(public_path() . '/upload/products/200/' . $img_name, 60);
                        Image::make($saved_img)->resize(600, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save(public_path() . '/upload/products/600/' . $img_name, 80);
                        $data['product_variation_id'] = $product_variation->id;
                        $data['img'] = $img_name;

                        ProductVariationImage::create($data);
                    }
                }

                if ($product_variation) {
                    $product_variation->attributeOptions()->sync($item['attributes']);

                    $result = '';
                    foreach ($product_variation->attributeOptions()->pluck('key')->toArray() as $item) {
                        $result .= '-' . $item['ru'];
                    }

                    is_null(optional($product_variation->attributeOptions)->first()) ? $item['slug'] = $product_variation->slug : $item['slug'] = $product_variation->slug . '-' . $result;
                    $item['slug'] = Str::slug($item['slug'], '-');
                    $product_variation->update($item);
                }
            }

            // delete option from attribute
            $after_update->productVariations()->whereNotIn('id', $item_ids)->delete();

            if ($product) {
                $after_update->categories()->sync($data['categories']);
            }

            DB::commit();

            return response(['message' => 'Успешно редактирован'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw ($e);
            return response(['message' => 'Ошибка'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $product = Product::find($id);
            foreach ($product->productVariations as $variation) {
                $variation->productVariationImages()->delete();
            }
            $product->productVariations()->delete();
            $product->delete();

            DB::commit();

            return back()->with([
                'success' => true,
                'message' => 'Успешно удален'
            ]);
            // return response(['message' => 'Успешно удален'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            // throw ($e);
            return back()->with([
                'success' => false,
                'message' => 'Error'
            ]);
            // return response(['message' => 'Ошибка'], 400);
        }
    }

    public function filter(Request $request)
    {
        $data = $request->all();

        $products = Product::with('categories', 'brand')->where('id', '>', 0);

        if (isset($data['brand_id'])) {
            $products->where('brand_id', $data['brand_id']);
        }
        if (isset($data['is_active'])) {
            $products->where('is_active', $data['is_active']);
        }
        if (isset($data['search'])) {
            $products->where('title->uz', 'like', '%' . $data['search'] . '%')->orWhere('title->ru', 'like', '%' . $data['search'] . '%');
        }
        if (isset($data['category_id'])) {
            $id = $data['category_id'];
            $products->whereHas('categories', function ($q) use ($id) {
                $q->where('category_id', $id);
            });
        }
        $products = $products->get();

        return response(['data' => $products], 200);
    }

    public function colors()
    {
        $colors = Color::all();

        return response([
            'colors' => $colors
        ], 200);
    }

    public function upload_from()
    {
        $url = 'http://213.230.65.189/Invema_Test/hs/invema_API/products';
        $url_auth = ['Venkon', 'overlord'];

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url, ['auth' =>  $url_auth]);
        $resp = (string) $res->getBody();
        $resp_toArray = json_decode($resp, true);

        if ($resp_toArray['success']) {
            $products = $resp_toArray['products'];

            foreach ($products as $item) {
                $productVariation = ProductVariation::where('venkon_id', $item['id'])->first();

                if (!$productVariation) {

                    $product = Product::where('vendor_code', $item['vendor_code'])->where('vendor_code', '!=', '')->first();

                    if (!$product) {
                        $product = new Product;
                    }
                    $product->title = json_decode($this->withLang($item['title']));
                    $product->vendor_code = $item['vendor_code'];
                    $product->brand_id = $item['brand_id'];
                    $product->is_active = false;
                    $product->save();

                    DB::table('category_product')->updateOrInsert([
                        'product_id' => $product->id,
                        'category_id' => $item['category_id']
                    ], [
                        'product_id' => $product->id,
                        'category_id' => $item['category_id']
                    ]);

                    $sub_data = ProductVariation::firstOrNew([
                        'venkon_id' => $item['id']
                    ]);
                    $sub_data->product_id = $product->id;
                    $sub_data->venkon_id = $item['id'];
                    $sub_data->color_id = $item['color_id'];
                    $sub_data->price = $item['price'];

                    if (isset($item['remainders'][0])) {
                        $summa = 0;
                        foreach ($item['remainders'] as $remainder) {
                            $summa += $remainder['remainder'];
                        }
                        $sub_data->remainder = $summa;
                    }

                    $color = Color::where('venkon_id', $item['color_id'])->first();

                    ProductVariation::where('slug', Str::slug($item['title'] . '-' . $item['vendor_code'] ?? '' . '-' . $color->title['ru'] ?? ''))->delete();

                    $sub_data->slug = Str::slug($item['title'] . '-' . $item['vendor_code'] ?? '' . '-' . $color->title['ru'] ?? '');
                    $sub_data->save();
                } else {
                    $productVariation->color_id = $item['color_id'];
                    $productVariation->price = $item['price'];

                    if (isset($item['remainders'][0])) {
                        $summa = 0;
                        foreach ($item['remainders'] as $remainder) {
                            $summa += $remainder['remainder'];
                        }
                        $productVariation->remainder = $summa;
                    }

                    $productVariation->save();

                    DB::table('category_product')->updateOrInsert([
                        'product_id' => $productVariation->product_id,
                        'category_id' => $item['category_id']
                    ], [
                        'product_id' => $productVariation->product_id,
                        'category_id' => $item['category_id']
                    ]);
                }
            }
        } else {
            return response(['message' => 'Ошибка со стороны сервера'], 400);
        }

        return back()->with(['message' => 'Success', 'success' => true]);
    }

    public function withLang($data)
    {
        return json_encode([
            'ru' => $data,
            'uz' => ''
        ]);
    }

    public function edit(Product $product, Request $request)
    {
        $prev_url = $_SERVER['HTTP_REFERER'];
        $url = parse_url($prev_url, PHP_URL_QUERY);
        parse_str($url, $get_pameters);
        
        if(!empty($get_pameters) && isset($get_pameters['page'])) {
            $page_number = $get_pameters['page'];
        } else {
            $page_number = null;
        }

        $languages = ['ru', 'uz'];
        $colors = Color::all();
        $brands = Brand::all();
        $categories = Category::all();

        return view('app.products.edit', compact('product', 'languages', 'colors', 'brands', 'categories', 'page_number'));
    }

    public function upload_from_dropzone(Request $request)
    {
        $img = $request->file('file');

        $img_name = Str::random(12) . '.' . $img->extension();
        $saved_img = $img->move(public_path('/upload/products'), $img_name);
        Image::make($saved_img)->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path() . '/upload/products/200/' . $img_name, 60);
        Image::make($saved_img)->resize(600, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path() . '/upload/products/600/' . $img_name, 80);

        return response(['file_name' => $img_name, 'variation_id' => $request->variation_id], 200);
    }
}
