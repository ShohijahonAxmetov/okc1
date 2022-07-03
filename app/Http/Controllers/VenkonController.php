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
use Illuminate\Support\Facades\Validator;

class VenkonController extends Controller
{
    public function discounts_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|max:255|unique:discounts,venkon_id',
            'discount_type' => 'required|in:order,brand,product,category',
            'amount_type' => 'required|in:percent,fixed',
            'brand_id' => 'nullable|max:255',
            'category_id' => 'nullable|max:255',
            'product_id' => 'nullable|max:255',
            'from_amount' => 'nullable|max:11',
            'to_amount' => 'nullable|max:11',
            'discount' => 'nullable|max:2'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $data = new Discount;
        $data->venkon_id = $request->id;
        $data->discount_type = $request->discount_type;
        $data->amount_type = $request->amount_type;
        $data->venkon_brand_id = $request->brand_id;
        $data->venkon_category_id = $request->category_id;
        $data->venkon_product_id = $request->product_id;
        $data->from_amount = $request->from_amount;
        $data->to_amount = $request->to_amount;
        $data->discount = $request->discount;
        $data->is_active = 1;
        $data->save();
        return response(['success' => true, 'data' => $request->all()], 200);
    }

    public function discounts_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'discount_type' => 'required|in:order,brand,product,category',
            'amount_type' => 'required|in:percent,fixed',
            'brand_id' => 'nullable|max:255',
            'category_id' => 'nullable|max:255',
            'product_id' => 'nullable|max:255',
            'from_amount' => 'nullable|max:11',
            'to_amount' => 'nullable|max:11',
            'discount' => 'nullable|max:2'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        if($discount = Discount::where('venkon_id', $id)->first()) {
            $data = $discount->update([
                'discount_type' => $request->discount_type,
                'amount_type' => $request->amount_type,
                'venkon_brand_id' => $request->brand_id,
                'venkon_category_id' => $request->category_id,
                'venkon_product_id' => $request->product_id,
                'from_amount' => $request->from_amount,
                'to_amount' => $request->to_amount,
                'discount' => $request->discount
            ]);
        } else {
            return response(['success' => false, 'message' => 'Discount not found'], 400);
        }
        
        return response(['success' => true, 'data' => $request->all()], 200);
    }

    public function discounts_delete(Request $request, $id)
    {
        if($discount = Discount::where('venkon_id', $id)->first()) {
            $data = $discount->update([
                'is_active' => false
            ]);
        } else {
            return response(['success' => false, 'message' => 'Discount not found'], 400);
        }

        return response(['success' => true], 200);
    }

    public function update_products_count(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'products' => 'required|array'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $warehouse = Warehouse::where('venkon_id', $request->id)->first();

        foreach($request->products as $product) {
            $warehouse->productVariations()->detach([$product['id']]);
            $warehouse->productVariations()->attach([$product['id']], ['remainder' => $product['remainder']]);
        }

        return response(['success' => true, 'warehouse' => $warehouse], 200);
    }

    public function warehouses_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|max:255|unique:warehouses,venkon_id',
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $data = new Warehouse;
        $data->venkon_id = $request->id;
        $data->title = $request->title;
        $data->is_active = 1;
        $data->save();
        return response(['success' => true, 'data' => $request->all()], 200);
    }

    public function warehouses_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        if($warehouse = Warehouse::where('venkon_id', $id)->first()) {
            $data = $warehouse->update([
                'title' => $request->title,
            ]);
        } else {
            return response(['success' => false, 'message' => 'Warehouse not found'], 400);
        }
        
        return response(['success' => true, 'data' => $request->all()], 200);
    }

    public function warehouses_delete(Request $request, $id)
    {
        if($warehouse = Warehouse::where('venkon_id', $id)) {
            $data = $warehouse->update([
                'is_active' => false
            ]);
        } else {
            return response(['success' => false, 'message' => 'Warehouse not found'], 400);
        }

        return response(['success' => true], 200);
    }

    public function brands_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|max:255|unique:brands,venkon_id',
            'title' => 'required|max:255',
            'link' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $data = new Brand;
        $data->venkon_id = $request->id;
        $data->title = $request->title;
        $data->link = $request->link;
        $data->is_active = 1;
        $data->save();
        return response(['success' => true, 'data' => $request->all()], 200);
    }

    public function brands_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'link' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $data = Brand::where('venkon_id', $id)->update([
            'title' => $request->title,
            'link' => $request->link
        ]);
        return response(['success' => true, 'data' => $request->all()], 200);
    }

    public function brands_delete(Request $request, $id)
    {
        $data = Brand::where('venkon_id', $id)->update([
            'is_active' => false
        ]);
        return response(['success' => true], 200);
    }

    public function categories_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|max:255|unique:categories,venkon_id',
            'title' => 'required|max:255',
            'parent_category_id' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $data = new Category;
        $data->venkon_id = $request->id;
        $data->title = json_decode($this->withLang($request->title));
        $data->parent_id = $request->parent_category_id;
        $data->is_active = 1;
        $data->save();
        return response(['success' => true, 'data' => $request->all()], 200);
    }

    public function categories_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'parent_category_id' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        if($category = Category::where('venkon_id', $id)->first()) {
            $data = $category->update([
                'title' => json_decode($this->withLang($request->title)),
                'parent_id' => $request->parent_category_id
            ]);
        } else {
            return response(['success' => false, 'message' => 'Category not found'], 400);
        }
        
        return response(['success' => true, 'data' => $request->all()], 200);
    }

    public function categories_delete(Request $request, $id)
    {
        if($category = Category::where('venkon_id', $id)->first()) {
            $data = $category->update([
                'is_active' => false
            ]);
        } else {
            return response(['success' => false, 'message' => 'Category not found'], 400);
        }
  
        return response(['success' => true], 200);
    }

    public function colors_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|max:255|unique:colors,venkon_id',
            'title' => 'required|max:255',
            'hex_code' => 'required|max:20',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
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
        $data->venkon_id = $request->id;
        $data->title = json_decode($this->withLang($request->title));
        $data->hex_code = $request->hex_code;
        $data->is_active = 1;
        $data->save();
        return response(['success' => true, 'data' => $request->all()], 200);
    }

    public function colors_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'hex_code' => 'required|max:20',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        if($color = Color::where('venkon_id', $id)->first()) {
            $data = $color->update([
                'title' => json_decode($this->withLang($request->title)),
                'hex_code' => $request->hex_code
            ]);
        } else {
            return response(['success' => false, 'message' => 'Color not found'], 400);
        }
        
        return response(['success' => true, 'data' => $request->all()], 200);
    }

    public function colors_delete(Request $request, $id)
    {
        if($color = Color::where('venkon_id', $id)) {
            $data = $color->update([
                'is_active' => false
            ]);
        } else {
            return response(['success' => false, 'message' => 'Color not found'], 400);
        }

        return response(['success' => true], 200);
    }

    public function products_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'vendor_code' => 'required|max:255',
            'category_id' => 'required|max:255',
            'brand_id' => 'required|max:255',
            'id' => 'required|max:255',
            'color_id' => 'required|max:255',
            'with_discount' => 'required|boolean',
            'price' => 'required|numeric',
            'discount_price' => 'required|numeric',
            'remainder' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        DB::beginTransaction();
        try {
            $data = Product::where('vendor_code',$request->vendor_code)->first();
            if(is_null($data)){
                $data = new Product;
            }
            $data->title = json_decode($this->withLang($request->title));
            $data->vendor_code = $request->vendor_code;
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
                'venkon_id' => $request->id
            ]);
            $sub_data->product_id = $data->id;
            $sub_data->venkon_id = $request->id;
            $sub_data->color_id = $request->color_id;
            $sub_data->with_discount = $request->with_discount;
            $sub_data->price = $request->price;
            $sub_data->discount_price = $request->discount_price;
            $sub_data->remainder = $request->remainder;
            $color = Color::where('venkon_id', $request->color_id)->first();
            if(!$color) {
                return response(['success' => false, 'message' => 'Color with this id does not exist. Firstly add color!'], 400);
            }
            $sub_data->slug = Str::slug($request->title.'-'.$request->vendor_code.'-'.$color->title['ru']);
            $sub_data->save();



            DB::commit();
            return response(['success' => true, 'data' => $request->all()], 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return response(['success' => false], 400);
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
            'with_discount' => 'required|boolean',
            'price' => 'required|numeric',
            'discount_price' => 'required|numeric',
            'remainder' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        DB::beginTransaction();
        try {
            $data = Product::where('vendor_code',$request->vendor_code)->first();
            $data->title = json_decode($this->withLang($request->title));
            $data->vendor_code = $request->vendor_code;
            $data->brand_id = $request->brand_id;
            $data->save();
            DB::table('category_product')->updateOrInsert([
                'product_id' => $data->id,
                'category_id' => $request->category_id
            ], [
                'product_id' => $data->id
            ]);
            $sub_data = ProductVariation::firstOrNew([
                'venkon_id' => $id
            ]);
            $sub_data->product_id = $data->id;
            $sub_data->venkon_id = $request->id;
            $sub_data->color_id = $request->color_id;
            $sub_data->with_discount = $request->with_discount;
            $sub_data->price = $request->price;
            $sub_data->discount_price = $request->discount_price;
            $sub_data->remainder = $request->remainder;
            $color = Color::where('venkon_id', $request->color_id)->first();
            if(!$color) {
                return response(['success' => false, 'message' => 'Color with this id does not exist. Firstly add color!'], 400);
            }
            $sub_data->slug = Str::slug($request->title.'-'.$request->vendor_code.'-'.$color->title['ru']);
            $sub_data->save();
            DB::commit();
            return response(['success' => true, 'data' => $request->all()], 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return response(['success' => false], 400);
        }
    }

    public function products_delete(Request $request, $id)
    {
        if($product = ProductVariation::where('venkon_id', $id)->first()) {
            $data = $product->update([
                'is_active' => false
            ]);
        } else {
            return response(['success' => false, 'message' => 'Product not found'], 400);    
        }
        
        return response(['success' => true], 200);
    }

    public function orders_put(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:collected,on_the_way,returned,done,cancelled'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $order = Order::find($id);
        $order->status = $request->status;
        $order->save();
        return response(['success' => true], 200);
    }

    public function withLang($data)
    {
        return json_encode([
            'ru' => $data,
            'uz' => ''
        ]);
    }
}
