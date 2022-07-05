<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\Discount;

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
        if(!$order) abort(404);
                        
        return view('app.orders.show', compact('order'));
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
        $data = $request->only('status');

        $validator = Validator::make($data, [
            'status' => 'required|in:new,collected,on_the_way,returned,done,cancelled'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $order = Order::find($id);
        if(!$order) {
            return response(['message' => 'Net takogo zakaza'], 400);
        }

        $order->update([
            'status' => $data['status']
        ]);

        return response(['message' => 'Успешно обновлен'], 200);
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

        if(!$order) {
            return response(['message' => 'Net takogo zakaza'], 400);
        }
        $order->update([
            'is_deleted' => 1
        ]);
        return response(['message' => 'Успешно удален'], 200);
    }
}
