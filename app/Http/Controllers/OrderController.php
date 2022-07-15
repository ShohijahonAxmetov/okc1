<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\Discount;
use App\Models\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::where('is_deleted', 0)->latest()->with('user', 'productVariations')->paginate(12);
        return view('app.orders.index', compact('orders'));
        // return response(['data' => $orders]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with('user', 'productVariations', 'productVariations.ProductVariationImages', 'productVariations.product', 'productVariations.product.brand')
            ->find($id);
        if (!$order) abort(404);

        $orders_history = Order::where('user_id', $order->user_id)
            ->with('user', 'productVariations', 'productVariations.ProductVariationImages', 'productVariations.product')
            ->get();

        $products_variations = ProductVariation::with('product')
            ->whereHas('product', function ($product) {
                $product->where('is_active', 1);
            })
            ->get();

        return view('app.orders.show', compact('order', 'orders_history', 'products_variations'));
        // return response(['data' => $order]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only('status', 'region', 'district', 'payment_method', 'delivery_method', 'postal_code', 'address');
        $data['with_delivery'] = $data['delivery_method'] ? 1 : 0;

        $validator = Validator::make($data, [
            'status' => 'required|in:new,collected,on_the_way,returned,done,cancelled',
            'region' => 'nullable|integer',
            'district' => 'nullable|integer',
            'payment_method' => 'required|in:cash,online',
            'delivery_method' => 'nullable|in:bts,delivery',
            'postal_code' => 'nullable|max:12'

        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $order = Order::find($id);
        if (!$order) {
            return response(['message' => 'Net takogo zakaza'], 400);
        }

        $order->update($data);

        return redirect()->route('orders.show', ['id' => $id])->with([
            'success' => true,
            'message' => 'Successfully updated'
        ]);
        // return response(['message' => 'Успешно обновлен'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return back()->with(['message' => 'Order not found', 'success' => false]);
            // return response(['message' => 'Net takogo zakaza'], 400);
        }
        $order->update([
            'is_deleted' => 1
        ]);
        return back()->with(['message' => 'Successfully deleted', 'success' => true]);
        // return response(['message' => 'Успешно удален'], 200);
    }

    public function edit($id)
    {
        $order = Order::with('user', 'productVariations', 'productVariations.ProductVariationImages', 'productVariations.product', 'productVariations.product.brand')
            ->find($id);
        if (!$order) abort(404);

        return view('app.orders.edit', compact('order'));
    }

    public function add_product($id, Request $request)
    {
        $request->validate([
            'variation_id' => 'integer|required',
            'count' => 'required|integer'
        ]);

        $product_variation = ProductVariation::find($request->variation_id);
        $amount = 0;
        if (isset($product_variation->discount_price)) {
            $amount += (preg_replace('/[^0-9]/', '', $product_variation->discount_price) * 100) * $request->count;
        } else {
            $brand_discount = isset($product_variation->product->brand->discount) ? $product_variation->product->brand->discount : null;
            if ($brand_discount) {
                $amount += (100 - $brand_discount->discount) / 100 * (preg_replace('/[^0-9]/', '', $product_variation->price) * 100) * $request->count;
            } else {
                $amount += (preg_replace('/[^0-9]/', '', $product_variation->price) * 100) * $request->count;
            }
        }

        DB::table('order_product_variation')->insert([
            'order_id' => $id,
            'product_variation_id' => $request->variation_id,
            'count' => $request->count,
            'price' => $amount,
            'discount_price' => 1,
            'brand_discount' => 1
        ]);
        unset($amount);

        Log::create([
            'admin_id' => auth()->user()->id,
            'model' => 'Order',
            'item_id' => $id,
            'action' => 'updated'
        ]);

        return back()->with([
            'success' => true
        ]);
    }

    public function delete_product($id, Request $request)
    {
        $request->validate([
            'order_id' => 'integer|required'
        ]);

        DB::table('order_product_variation')
            ->where('product_variation_id', $id)
            ->where('order_id', $request->order_id)
            ->delete();

        Log::create([
            'admin_id' => auth()->user()->id,
            'model' => 'Order',
            'item_id' => $request->order_id,
            'action' => 'updated'
        ]);

        return back()->with([
            'success' => true
        ]);
    }
}
