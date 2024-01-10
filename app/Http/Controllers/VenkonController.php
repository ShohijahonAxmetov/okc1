<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Order;
use App\Models\Warehouse;
use App\Models\Discount;
use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\LogOneC;
use Illuminate\Support\Facades\Validator;

class VenkonController extends Controller
{
    public function discounts_create(Request $request)
    {
        LogOneC::create([
            'model' => 'discount',
            'action' => 'create',
            'response_from_1c' => json_encode($request->all())
        ]);
        $validator = Validator::make($request->all(), [
            'discount_type' => 'required|in:order,brand,product,category',
            'amount_type' => 'required|in:percent,fixed',
            'integration_id' => 'required|max:255',
            'brand_id' => 'nullable|max:255',
            'category_id' => 'nullable|max:255',
            'products' => 'nullable',
            'from_amount' => 'nullable|max:11',
            'to_amount' => 'nullable|max:11',
            'discount' => 'nullable|max:2'
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }

        if ($discount = Discount::where('integration_id', $id)->first()) {
            $data = $discount->update([
                'discount_type' => $request->discount_type,
                // 'integration_id' => $request->integration_id, // delete
                'amount_type' => $request->amount_type,
                'venkon_brand_id' => $request->brand_id,
                'venkon_category_id' => $request->category_id,
                'venkon_product_id' => $request->products,
                'from_amount' => $request->from_amount,
                'to_amount' => $request->to_amount,
                'discount' => $request->discount,
                'is_active' => 1
            ]);
        } else {
            $data = new Discount;
            // $data->venkon_id = $request->id;
            $data->integration_id = $request->integration_id;
            $data->discount_type = $request->discount_type;
            $data->amount_type = $request->amount_type;
            $data->venkon_brand_id = $request->brand_id;
            $data->venkon_category_id = $request->category_id;
            $data->venkon_product_id = $request->products;
            $data->from_amount = $request->from_amount;
            $data->to_amount = $request->to_amount;
            $data->discount = $request->discount;
            $data->is_active = 1;
            $data->save();
            // return response([
            //     'success' => false,
            //     'message' => 'Discount not found'
            // ], 400);
        }

        return response([
            'success' => true,
            'data' => $request->all()
        ], 200);
    }

    public function discounts_put(Request $request, $id)
    {
        LogOneC::create([
            'model' => 'discount',
            'action' => 'update',
            'response_from_1c' => json_encode($request->all())
        ]);
        $validator = Validator::make($request->all(), [
            'discount_type' => 'required|in:order,brand,product,category',
            'amount_type' => 'required|in:percent,fixed',
            // 'integration_id' => 'required|max:255',
            'brand_id' => 'nullable|max:255',
            'category_id' => 'nullable|max:255',
            'products' => 'nullable',
            'from_amount' => 'nullable|max:11',
            'to_amount' => 'nullable|max:11',
            'discount' => 'nullable|max:2'
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }

        if ($discount = Discount::where('integration_id', $id)->first()) {
            $data = $discount->update([
                'discount_type' => $request->discount_type,
                // 'integration_id' => $request->integration_id, // delete
                'amount_type' => $request->amount_type,
                'venkon_brand_id' => $request->brand_id,
                'venkon_category_id' => $request->category_id,
                'venkon_product_id' => $request->products,
                'from_amount' => $request->from_amount,
                'to_amount' => $request->to_amount,
                'discount' => $request->discount,
                'is_active' => 1
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Discount not found'
            ], 400);
        }

        return response([
            'success' => true,
            'data' => $request->all()
        ], 200);
    }

    public function discounts_delete($id)
    {
        LogOneC::create([
            'model' => 'discount',
            'action' => 'delete',
            'response_from_1c' => $id
        ]);
        if ($discount = Discount::where('integration_id', $id)->first()) {
            $discount->update([
                'is_active' => false
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Discount not found'
            ], 400);
        }

        return response([
            'success' => true
        ], 200);
    }

    public function update_products_count(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'remainders' => 'required',
            'remainders.*.id' => 'required',
            'remainders.*.products' => 'required|array',
            'remainders.*.products.*.id' => 'required',
            'remainders.*.products.*.remainder' => 'required'
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }
        $data = $request->all()['remainders'];

        foreach($data as $item) {
            $warehouse = Warehouse::where('integration_id', $item['id'])->first();

            foreach ($item['products'] as $product) {
                $warehouse->productVariations()->detach([$product['id']]);
                $warehouse->productVariations()->attach(
                    [$product['id']],
                    ['remainder' => $product['remainder']]
                );


                $variation = ProductVariation::where('integration_id', $product['id'])
                                    ->first();
                $total_remainder = $variation->warehouses->sum('pivot.remainder');
                $variation->update(['remainder' => $total_remainder]);
            }
        }

        return response([
            'success' => true,
        ], 200);
    }

    public function warehouses_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'id' => 'required|max:255|unique:warehouses,venkon_id',
            'integration_id' => 'required|max:255',
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }

        $warehouse = Warehouse::where('integration_id', $request->integration_id)
            ->first();
        if(!$warehouse) {
            $validator = Validator::make($request->all(), [
                // 'id' => 'required|max:255|unique:warehouses,venkon_id',
                'integration_id' => 'required|max:255|unique:warehouses,integration_id',
                'title' => 'required',
            ]);
            if ($validator->fails()) {
                return response([
                    'message' => $validator->errors()
                ], 400);
            }
            $data = new Warehouse;
            // $data->venkon_id = $request->id;
            $data->integration_id = $request->integration_id;
            $data->title = $request->title;
            $data->is_active = 1;
            $data->save();
            return response([
                'success' => true,
                'data' => $request->all()
            ], 200);
        }

        $warehouse->update([
            'title' => $request->title,
            'is_active' => 1
        ]);  

        return response([
            'success' => true,
            'data' => $request->all()
        ], 200);      
    }

    public function warehouses_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            // 'integration_id' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }

        if ($warehouse = Warehouse::where('integration_id', $id)->first()) {
            $data = $warehouse->update([
                'title' => $request->title,
                // 'integration_id' => $request->integration_id,
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Warehouse not found'
            ], 400);
        }

        return response([
            'success' => true,
            'data' => $request->all()
        ], 200);
    }

    public function warehouses_delete(Request $request, $id)
    {
        if ($warehouse = Warehouse::where('integration_id', $id)) {
            $data = $warehouse->update([
                'is_active' => false
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Warehouse not found'
            ], 400);
        }

        return response([
            'success' => true
        ], 200);
    }

    public function brands_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'id' => 'required|max:255|unique:brands,venkon_id',
            'integration_id' => 'required|max:255|unique:brands,integration_id',
            'title' => 'required|max:255',
            'link' => 'max:255',
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }
        $data = new Brand;
        // $data->venkon_id = $request->id;
        $data->integration_id = $request->integration_id;
        $data->title = $request->title;
        $data->link = $request->link ?? null;
        $data->is_active = 1;
        $data->save();
        return response([
            'success' => true,
            'data' => $request->all()
        ], 200);
    }

    public function brands_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'link' => 'max:255',
            // 'integration_id' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }
        $data = Brand::where('integration_id', $id)->update([
            'title' => $request->title,
            'link' => $request->link ?? null,
            // 'integration_id' => $request->integration_id,
        ]);
        return response([
            'success' => true,
            'data' => $request->all()
        ], 200);
    }

    public function brands_delete($id)
    {
        Brand::where('integration_id', $id)
            ->first()
            ->update([
                'is_active' => false
            ]);

        return response([
            'success' => true
        ], 200);
    }

    public function categories_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'integration_id' => 'required|max:255',
            'title' => 'required|max:255',
            'parent_category_id' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }

        $category = Category::where('integration_id', $request->integration_id)
            ->first();
        if(!$category) {

            $validator = Validator::make($request->all(), [
                // 'id' => 'required|max:255|unique:categories,venkon_id',
                'integration_id' => 'required|max:255|unique:categories,integration_id',
                'title' => 'required|max:255',
                'parent_category_id' => 'required|max:255',
            ]);
            if ($validator->fails()) {
                return response([
                    'message' => $validator->errors()
                ], 400);
            }
            $data = new Category;
            // $data->venkon_id = $request->id;
            $data->integration_id = $request->integration_id;
            $data->title = json_decode($this->withLang($request->title));
            $data->parent_id = $request->parent_category_id;
            $data->is_active = 1;
            $data->save();

            return response([
                'success' => true,
                'data' => $request->all()
            ], 200);
        }

        $category->update([
            'title' => json_decode($this->withLang($request->title)),
            'parent_id' => $request->parent_category_id,
            'is_active' => 1
        ]);

        return response([
            'success' => true,
            'data' => $request->all()
        ], 200);
        
    }

    public function categories_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'parent_category_id' => 'required|max:255',
            // 'integration_id' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }

        if ($category = Category::where('integration_id', $id)->first()) {
            $data = $category->update([
                'title' => json_decode($this->withLang($request->title)),
                'parent_id' => $request->parent_category_id,
                // 'integration_id' => $request->integration_id,
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Category not found'
            ], 400);
        }

        return response([
            'success' => true,
            'data' => $request->all()
        ], 200);
    }

    public function categories_delete(Request $request, $id)
    {
        if ($category = Category::where('integration_id', $id)->first()) {
            $data = $category->update([
                'is_active' => false
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Category not found'
            ], 400);
        }

        return response([
            'success' => true
        ], 200);
    }

    public function colors_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'id' => 'required|max:255|unique:colors,venkon_id',
            'integration_id' => 'required|max:255|unique:colors,integration_id',
            'title' => 'required|max:255',
            'hex_code' => 'required|max:20',
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }

        // DB::beginTransaction();

        // try {
        //     $data['title'] = json_decode($data['title']);

        //     $attribute = Attribute::create($data);

        //     if($attribute && isset($data['options'])) {
        //         $data['options'] = json_decode($data['options'], true);
        //         foreach ($data['options'] as $item) {
        //             $item['attribute_id'] = $attribute->id;

        //             AttributeOption::create($item);
        //         }
        //     }

        //     DB::commit();

        //     return response(['message' => 'Успешно добавлен'], 200);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     throw($e);
        //     return response(['message' => 'Ошибка'], 400);
        // }

        // $data = new Attribute;
        // $data->

        $data = new Color;
        // $data->venkon_id = $request->id;
        $data->integration_id = $request->integration_id;
        $data->title = json_decode($this->withLang($request->title));
        $data->hex_code = $request->hex_code;
        $data->is_active = 1;
        $data->save();

        return response([
            'success' => true,
            'data' => $request->all()
        ], 200);
    }

    public function colors_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'hex_code' => 'required|max:20',
            // 'integration_id' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }

        if ($color = Color::where('integration_id', $id)->first()) {
            $data = $color->update([
                'title' => json_decode($this->withLang($request->title)),
                'hex_code' => $request->hex_code,
                // 'integration_id' => $request->integration_id,
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Color not found'
            ], 400);
        }

        return response([
            'success' => true,
            'data' => $request->all()
        ], 200);
    }

    public function colors_delete(Request $request, $id)
    {
        if ($color = Color::where('integration_id', $id)) {
            $data = $color->update([
                'is_active' => false
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Color not found'
            ], 400);
        }

        return response([
            'success' => true
        ], 200);
    }

    public function products_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'vendor_code' => 'required|max:255',
            'category_id' => 'required|max:255',
            'brand_id' => 'required|max:255',
            // 'id' => 'required|max:255',
            'integration_id' => 'required|max:255',
            // 'color_id' => 'required|max:255',
            // 'with_discount' => 'required|boolean',
            'price' => 'required|numeric',
            // 'discount_price' => 'required|numeric',
            'remainder' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }
        DB::beginTransaction();
        try {
            $data = Product::where('vendor_code', $request->vendor_code)
                ->where('vendor_code', '!=', '')
                ->where('vendor_code', '!=', 0)
                ->first();

            if (!$data) {
                $data = new Product;
            }
            $data->title = json_decode($this->withLang($request->title));
            $data->vendor_code = $request->vendor_code == 0 ? '' : $request->vendor_code;
            $data->brand_id = $request->brand_id;
            $data->is_active = false;
            $data->save();
            DB::table('category_product')->updateOrInsert([
                'product_id' => $data->id,
                'category_id' => $request->category_id
            ], [
                'product_id' => $data->id
            ]);
            $sub_data = ProductVariation::firstOrNew([
                'integration_id' => $request->integration_id
            ]);
            $sub_data->product_id = $data->id;
            // $sub_data->venkon_id = $request->id;
            $sub_data->integration_id = $request->integration_id;
            $sub_data->color_id = $request->color_id;
            $sub_data->with_discount = 0;
            $sub_data->price = $request->price;
            $sub_data->discount_price = null;
            $sub_data->remainder = $request->remainder;
            $color = Color::where('integration_id', $request->color_id)->first();
            if (!$color) {
                $color_result = '';
            } else {
                $color_result = $color->title['ru'];
            }
            $sub_data->slug = Str::slug($request->title . '-' . $request->vendor_code . '-' . $color_result);
            $sub_data->save();

            DB::commit();
            return response([
                'success' => true,
                'data' => $request->all()
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return response([
                'success' => false
            ], 400);
        }
    }

    public function products_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'vendor_code' => 'required|max:255',
            'category_id' => 'required|max:255',
            'brand_id' => 'required|max:255',
            'color_id' => 'required|max:255',
            // 'with_discount' => 'required|boolean',
            // 'integration_id' => 'required|max:255',
            'price' => 'required|numeric',
            // 'discount_price' => 'required|numeric',
            'remainder' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()
            ], 400);
        }
        DB::beginTransaction();
        try {

            $productVariation = ProductVariation::where('integration_id', $id)
                    ->first();
                    
            if (!$productVariation) {

                $product = Product::where('vendor_code', $request->vendor_code)
                    ->where('vendor_code', '!=', '')
                    ->first();

                if (!$product) {
                    $product = new Product;
                }
                $product->title = json_decode($this->withLang($request->title));
                $product->vendor_code = $request->vendor_code == 0 ? '' : $request->vendor_code;
                $product->brand_id = $request->brand_id;
                $product->is_active = false;
                $product->save();

                DB::table('category_product')->updateOrInsert([
                    'product_id' => $product->id,
                    'category_id' => $request->category_id
                ], [
                    'product_id' => $product->id,
                    'category_id' => $request->category_id
                ]);

                $sub_data = ProductVariation::firstOrNew([
                    'integration_id' => $id
                ]);
                $sub_data->product_id = $product->id;
                // $sub_data->venkon_id = $request->id;
                $sub_data->integration_id = $id;
                $sub_data->color_id = $request->color_id;
                $sub_data->price = $request->price;
                $sub_data->remainder = $request->remainder;

                // if (isset($request->remainders[0])) {
                //     $summa = 0;
                //     foreach ($request->remainders as $remainder) {
                //         $summa += $remainder['remainder'];
                //     }
                //     $sub_data->remainder = $summa;
                // } else {
                //     $sub_data->remainder = 0;
                // }

                $color = Color::where('integration_id', $request->color_id)
                    ->first();

                $dublicate_data = ProductVariation::where('slug', Str::slug($request->title . '-' . $request->vendor_code ?? '' . '-' . $color->title['ru'] ?? ''))
                    ->first();

                if (isset($dublicate_data)) {
                    $sub_data->slug = Str::slug($request->title . '-' . $request->vendor_code ?? '' . '-' . $color->title['ru'] ?? '') . '-dublicate';
                } else {
                    $sub_data->slug = Str::slug($request->title . '-' . $request->vendor_code ?? '' . '-' . $color->title['ru'] ?? '');
                }
                $sub_data->save();
            } else {
                // $productVariation->integration_id = $request->integration_id;
                $productVariation->color_id = $request->color_id;
                $productVariation->price = $request->price;
                $productVariation->remainder = $request->remainder;

                // if (isset($request->remainders[0])) {
                //     $summa = 0;
                //     foreach ($request->remainders as $remainder) {
                //         $summa += $remainder['remainder'];
                //     }
                //     $productVariation->remainder = $summa;
                // } else {
                //     $productVariation->remainder = 0;
                // }

                $productVariation->save();

                // update productVariation->product
                $product = $productVariation->product;

                $product->update([
                    'brand_id' => $request->brand_id,
                    'title' => json_decode($this->withLang($request->title))
                ]);

                DB::table('category_product')->updateOrInsert([
                    'product_id' => $productVariation->product_id,
                    'category_id' => $request->category_id
                ], [
                    'product_id' => $productVariation->product_id,
                    'category_id' => $request->category_id
                ]);
            }


            DB::commit();
            return response([
                'success' => true,
                'data' => $request->all()
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return response([
                'success' => false
            ], 400);
        }
    }

    public function products_delete(Request $request, $id)
    {
        if ($product = ProductVariation::where('integration_id', $id)->first()) {
            $data = $product->update([
                'is_active' => false
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Product not found'
            ], 400);
        }

        return response([
            'success' => true
        ], 200);
    }

    public function orders_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:collected,on_the_way,returned,done,cancelled,accepted'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $order = Order::find($id);
        $order->status = $request->status;
        if($request->status == 'done') $order->is_payed = 1;
        $order->save();
        return response([
            'success' => true
        ], 200);
    }

    public function withLang($data)
    {
        return json_encode([
            'ru' => $data,
            'uz' => ''
        ]);
    }

    public function upload_datas()
    {
        // $new_ip_address = '94.232.24.102';
        $new_ip_address = env('C_IP');
        $old_ip_address = '213.230.65.189';

        $base_url = 'http://' . $new_ip_address . '/UT_NewClean/hs/invema_API';
        $old_base_url = 'http://' . $new_ip_address . '/invema/hs/invema_API';

        /*
         * upload brands from venkom
         */
        $url = $base_url.'/brands';
        $url_auth = ['Venkon', 'overlord'];

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url, ['auth' =>  $url_auth]);
        $resp = (string) $res->getBody();
        $resp_toArray = json_decode($resp, true);
        if ($resp_toArray['success']) {
            $brands = $resp_toArray['brands'];

            foreach ($brands as $item) {
                Brand::updateOrCreate([
                    'integration_id' => $item['integration_id']
                ], [
                    'title' => $item['name'],
                    'link' => $item['link'],
                    // 'integration_id' => $item['integration_id'],
                    'is_active' => 1
                ]);
            }
        } else {
            return response([
                'message' => 'Ошибка со стороны сервера'
            ], 400);
        }

        /*
         * upload categories from venkom
         */
        $url = $base_url.'/categories';

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url, ['auth' =>  $url_auth]);
        $resp = (string) $res->getBody();
        $resp_toArray = json_decode($resp, true);

        if ($resp_toArray['success']) {
            $categories = $resp_toArray['categories'];

            foreach ($categories as $item) {
                Category::updateOrCreate([
                    'integration_id' => $item['integration_id']
                ], [
                    'title' => json_decode($this->withLang($item['title'])),
                    'parent_category_id' => $item['parent_category_id'],
                    // 'integration_id' => $item['integration_id'],
                    'slug' => Str::slug($item['title'], '-')
                ]);
            }
        } else {
            return response([
                'message' => 'Ошибка со стороны сервера'
            ], 400);
        }

        /*
         * upload colors from venkom
         */
        $url = $base_url.'/colors';

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url, ['auth' =>  $url_auth]);
        $resp = (string) $res->getBody();
        $resp_toArray = json_decode($resp, true);

        if ($resp_toArray['success']) {
            $colors = $resp_toArray['colors'];

            foreach ($colors as $item) {
                Color::updateOrCreate([
                    'integration_id' => $item['integration_id']
                ], [
                    'title' => json_decode($this->withLang($item['title'])),
                    'hex_code' => $item['hex_code'],
                    // 'integration_id' => $item['integration_id'],
                    'is_active' => 1
                ]);
            }
        } else {
            return response([
                'message' => 'Ошибка со стороны сервера'
            ], 400);
        }

        /*
         * upload products from venkom
         */
        $url = $base_url.'/products';
        $url_auth = ['Venkon', 'overlord'];

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url, ['auth' =>  $url_auth]);
        $resp = (string) $res->getBody();
        $resp_toArray = json_decode($resp, true);
        // return response($resp_toArray);

        if ($resp_toArray['success']) {
            $products = $resp_toArray['products'];
            // return response($products);
            foreach ($products as $item) {
                $productVariation = ProductVariation::where('integration_id', $item['integration_id'])
                    ->first();
                $summa = 0;
                
                if (!$productVariation) {

                    $product = Product::where('vendor_code', $item['vendor_code'])
                        ->where('vendor_code', '!=', '')
                        ->first();

                    if (!$product) {
                        $product = new Product;
                        $product->title = json_decode($this->withLang($item['title']));
                    }
                    // $product->title = $product->title;
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
                        'integration_id' => $item['integration_id']
                    ]);
                    $sub_data->product_id = $product->id;
                    // $sub_data->venkon_id = $item['id'];
                    $sub_data->integration_id = $item['integration_id'];
                    $sub_data->color_id = $item['color_id'];
                    $sub_data->price = $item['price'];

                    if (isset($item['remainders'][0])) {
                        foreach ($item['remainders'] as $remainder) {
                            $summa += $remainder['remainder'];
                        }
                        $sub_data->remainder = $summa;
                    } else {
                        $sub_data->remainder = 0;
                    }

                    // esli ne ostalos tovari na skladax, deaktiviruem produkt
                    if($summa == 0) {
                        $product->update([
                            'is_active' => 0
                        ]);
                    }

                    $color = Color::where('integration_id', $item['color_id'])
                        ->first();

                    $dublicate_data = ProductVariation::where('slug', Str::slug($item['title'] . '-' . $item['vendor_code'] ?? '' . '-' . $color->title['ru'] ?? ''))
                        ->first();

                    if (isset($dublicate_data)) {
                        $sub_data->slug = Str::slug($item['title'] . '-' . $item['vendor_code'] ?? '' . '-' . $color->title['ru'] ?? '') . '-dublicate';
                    } else {
                        $sub_data->slug = Str::slug($item['title'] . '-' . $item['vendor_code'] ?? '' . '-' . $color->title['ru'] ?? '');
                    }
                    $sub_data->save();
                } else {
                    $productVariation->color_id = $item['color_id'];
                    $productVariation->price = $item['price'];
                    // $productVariation->integration_id = $item['integration_id'];

                    if (isset($item['remainders'][0])) {
                        $summa = 0;
                        foreach ($item['remainders'] as $remainder) {
                            $summa += $remainder['remainder'];
                        }
                        $productVariation->remainder = $summa;
                    } else {
                        $productVariation->remainder = 0;
                    }

                    $productVariation->save();

                    // update productVariation->product
                    $product = $productVariation->product;
                    if($product) {
                        $product->update([
                            'brand_id' => $item['brand_id'],
                            // 'title' => json_decode($this->withLang($item['title']))
                        ]);

                        // esli ne ostalos tovari na skladax, deaktiviruem produkt
                        if($summa == 0) {
                            $product->update([
                                'is_active' => 0
                            ]);
                        }
                    }
 
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
            return response([
                'message' => 'Ошибка со стороны сервера'
            ], 400);
        }

        /*
         * upload discount from venkom
         */
        $url = $base_url.'/discount';
        $url_auth = ['Venkon', 'overlord'];

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url, ['auth' =>  $url_auth]);
        $resp = (string) $res->getBody();
        $resp_toArray = json_decode($resp, true);

        if ($resp_toArray['success']) {

            $discount = $resp_toArray['discount'];

            $discounts_id = [];
            foreach ($discount as $item) {
                if ($item['discount_type']) {

                    $discounts_id[] = $item['integration_id'];

                    $var = Discount::updateOrCreate([
                        'integration_id' => $item['integration_id']
                    ], [
                        'venkon_category_id' => $item['category_id'],
                        'venkon_brand_id' => $item['brand_id'],
                        'venkon_product_id' => $item['products'],
                        'discount_type' => $item['discount_type'],
                        'amount_type' => $item['amount_type'],
                        'discount' => $item['discount'],
                        // 'integration_id' => $item['integration_id'],
                        'from_amount' => $item['from_amount'],
                        'to_amount' => $item['to_amount'],
                        'is_active' => 1
                    ]);

                    switch ($var->discount_type) {
                            // esli dan skidki na tovari, obnovlyaem skidochnuyu cenu tovarov
                        case 'product':
                            foreach ($var->venkon_product_id as $product) {
                                $product_variation = ProductVariation::where('integration_id', $product)
                                    ->first();

                                if($product_variation) {
                                    if ($var->amount_type == 'percent') {
                                        $product_variation->update([
                                            'discount_price' => (100 - $var->discount) / 100 * $product_variation->price
                                        ]);
                                    } else if ($var->amount_type == 'fixed') {
                                        $product_variation->update([
                                            'discount_price' => $product_variation->price - $var->discount
                                        ]);
                                    }
                                }
                                
                            }
                            break;
                            // esli dan skidki na brand, obnovlyaem skidochnie ceni vsex tovarov brenda
                        case 'brand':
                            $brand = Brand::where('integration_id', $var->venkon_brand_id)
                                ->first();

                            // if(!$brand) {
                            //     $brand = new Brand;
                            //     $brand->venkon_id = $var->venkon_id;
                            //     $brand->title = $var->venkon_id;
                            //     $brand->save();
                            // }
                            if($brand) {
                                foreach ($brand->products as $product) {
                                    foreach ($product->productVariations as $variation) {

                                        if ($var->amount_type == 'percent') {
                                            $variation->update([
                                                'discount_price' => (100 - $var->discount) / 100 * $variation->price
                                            ]);
                                        } else if ($var->amount_type == 'fixed') {
                                            $variation->update([
                                                'discount_price' => $variation->price - $var->discount
                                            ]);
                                        }
                                    }
                                }
                            }
                            
                            break;
                            // esli dan skidki na kategoriyu, obnovlyaem skidochnie ceni vsex tovarov kategorii
                        case 'category':
                            $category = Category::where('integration_id', $var->venkon_category_id)
                                ->first();
                            foreach ($category->products as $product) {
                                foreach ($product->productVariations as $variation) {

                                    if ($var->amount_type == 'percent') {
                                        $variation->update([
                                            'discount_price' => (100 - $var->discount) / 100 * $variation->price
                                        ]);
                                    } else if ($var->amount_type == 'fixed') {
                                        $variation->update([
                                            'discount_price' => $variation->price - $var->discount
                                        ]);
                                    }
                                }
                            }
                            break;
                    }
                }
            }
            $vars = Discount::whereNotIn('integration_id', $discounts_id)
                ->get();

            foreach ($vars as $var) {
                switch ($var->discount_type) {
                    // esli dan skidki na tovari, obnovlyaem skidochnuyu cenu tovarov
                    case 'product':
                        foreach ($var->venkon_product_id as $product) {
                            $product_variation = ProductVariation::where('integration_id', $product)
                                ->first();

                            $product_variation->update([
                                'discount_price' => null
                            ]);

                        }
                        break;
                    // esli dan skidki na brand, obnovlyaem skidochnie ceni vsex tovarov brenda
                    case 'brand':
                        $brand = Brand::where('integration_id', $var->venkon_brand_id)
                            ->first();
                        foreach ($brand->products as $product) {
                            foreach ($product->productVariations as $variation) {

                                $product_variation->update([
                                    'discount_price' => null
                                ]);

                            }
                        }
                        break;
                    // esli dan skidki na kategoriyu, obnovlyaem skidochnie ceni vsex tovarov kategorii
                    case 'category':
                        $category = Category::where('integration_id', $var->venkon_category_id)
                            ->first();
                        foreach ($category->products as $product) {
                            foreach ($product->productVariations as $variation) {

                                $product_variation->update([
                                    'discount_price' => null
                                ]);
                                
                            }
                        }
                        break;
                }
                $var->update([
                    'is_active' => 0
                ]);
            }
        } else {
            return response([
                'message' => 'Ошибка со стороны сервера'
            ], 400);
        }

        /*
         * upload warehouses from venkom
         */
        $url = $base_url.'/warehouses';

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url, ['auth' =>  $url_auth]);
        $resp = (string) $res->getBody();
        $resp_toArray = json_decode($resp, true);

        if ($resp_toArray['success']) {
            $warehouses = $resp_toArray['warehouses'];

            foreach ($warehouses as $item) {
                Warehouse::updateOrCreate([
                    'integration_id' => $item['integration_id']
                ], [
                    'title' => $item['title'],
                    // 'integration_id' => $item['integration_id'],
                    'is_active' => 1
                ]);
            }
        } else {
            return response([
                'message' => 'Ошибка со стороны сервера'
            ], 400);
        }

        /*
         * upload warehouses and warehouses products
         */
        $url = $base_url.'/remainder';

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url, ['auth' =>  $url_auth]);
        $resp = (string) $res->getBody();
        $resp_toArray = json_decode($resp, true);

        if ($resp_toArray['success']) {
            $remainder = $resp_toArray['remainder'];
            DB::table('product_variation_warehouse')->truncate();
            foreach ($remainder as $item) {
                foreach ($item['products'] as $product) {
                    DB::table('product_variation_warehouse')
                        ->insert([
                            'warehouse_id' => $item['id'],
                            'product_variation_id' => $product['id'],
                            'remainder' => $product['remainder']
                        ]);
                }
            }
        } else {
            return response([
                'message' => 'Ошибка со стороны сервера'
            ], 400);
        }


        return back()->with([
            'message' => 'Успешно загружен!',
            'success' => true
        ]);
    }
}
