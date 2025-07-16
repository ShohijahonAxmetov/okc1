<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Warehouse;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\Log;
use Exception;
use App\Http\Controllers\FargoController;
use App\Traits\Playmobile;

class OrderController extends Controller
{
    use Playmobile;

    public function index()
    {
        $orders = Order::where('is_deleted', 0)
            ->latest()
            ->with('user', 'productVariations', 'productVariations.warehouses');

        if (isset($_GET['date']) && $_GET['date'] != '') {
            $orders = $orders->whereDate('created_at', $_GET['date']);
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $orders = $orders->where('id', trim($_GET['search']))
                ->orWhere('name', 'like', trim('%' . $_GET['search'] . '%'))
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', trim('%' . $_GET['search'] . '%'));
                });
        }

        $orders = $orders->paginate(24);

        $search = $_GET['search'] ?? '';
        $date = $_GET['date'] ?? '';

        return view('app.orders.index', compact(
            'orders',
            'search',
            'date',
        ));
        // return response(['data' => $orders]);
    }


    public function store(Request $request)
    {
    }

    public function show($id)
    {
        $order = Order::with('user', 'productVariations', 'productVariations.ProductVariationImages', 'productVariations.product', 'productVariations.product.brand', 'zoodpayHistories')
            ->find($id);
        if (!$order) abort(404);

        $orders_history = Order::where('user_id', $order->user_id)
            ->latest()
            ->with('user', 'productVariations', 'productVariations.ProductVariationImages', 'productVariations.product')
            ->get();

        $products_variations = ProductVariation::with('product')
            ->whereHas('product', function ($product) {
                $product->where('is_active', 1);
            })
            ->get();

        $warehouses = Warehouse::latest()
            ->where('is_active', 1)
            ->has('productVariations')
            ->get();

        return view('app.orders.show', compact(
            'order',
            'orders_history',
            'products_variations',
            'warehouses'
        ));
        // return response(['data' => $order]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only('status', 'region', 'district', 'payment_method', 'delivery_method', 'postal_code', 'address', 'warehouse_id', 'with_delivery');
        // $data['with_delivery'] = $data['delivery_method'] ? 1 : 0;

        $validator = Validator::make($data, [
            'status' => 'required|in:new,collected,on_the_way,returned,done,cancelled,accepted',
            'region' => 'nullable|integer',
            'district' => 'nullable|integer',
            'payment_method' => 'required|in:cash,online',
            'delivery_method' => 'nullable|in:bts,delivery',
            'postal_code' => 'nullable|max:12'

        ]);
        if ($validator->fails()) {
            return back()->with([
                'success' => false,
                'message' => 'Validator fails'
            ], 400);
        }

        $order = Order::find($id);
        if (!$order) {
            return response([
                'success' => false,
                'message' => 'Order not found'
            ], 400);
        }

        // nujno li sozdat zakaz na fargo
        $send_fargo = true;
        if($order->status != 'new') {
            $send_fargo = false;
        }

        // nujno li otnimat iz ostatka 1c
        $send_1c = true;
        if($order->status != 'new') {
            $send_1c = false;
        }

        // old status
        $old_status = $order->status; 

        DB::beginTransaction();
        try {
            $order->update($data);

            // create fargo item
            if ($order->with_delivery == 1 && $order->status == 'accepted' && $send_fargo) {
                $fargo = new FargoController;
                $fargo->create_order($order);
            }

            // // send message to clients about status of order (Playmobile)
            // if($order->status != $old_status) {
            //     $this->order_status_send($order);
            // }


            // // send data to 1c for update remainder
            if($send_1c || ($order->status == 'returned' && $old_status != 'returned')) {
                $res = $this->order_to_venkom($order);

                if(!$res['success']) {
                    return response([
                        'success' => false,
                        'message' => $res['error']
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('orders.show', ['id' => $id])->with([
                'success' => false,
                'message' => 'Ошибка транзакции'
            ]);
        }

        return redirect()->route('orders.show', ['id' => $id])->with([
            'success' => true,
            'message' => 'Успешно обновлен'
        ]);
        // return response(['message' => 'Успешно обновлен'], 200);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return back()->with(['message' => 'Нет такого заказа', 'success' => false]);
            // return response(['message' => 'Net takogo zakaza'], 400);
        }
        $order->update([
            'is_deleted' => 1
        ]);
        return back()->with(['message' => 'Успешно удален', 'success' => true]);
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
            $amount += preg_replace('/[^0-9]/', '', $product_variation->discount_price) * $request->count;
        } else {
            $brand_discount = isset($product_variation->product->brand->discount) ? $product_variation->product->brand->discount : null;
            if ($brand_discount) {
                $amount += (100 - $brand_discount->discount) / 100 * preg_replace('/[^0-9]/', '', $product_variation->price) * $request->count;
            } else {
                $amount += preg_replace('/[^0-9]/', '', $product_variation->price) * $request->count;
            }
        }

        DB::beginTransaction();
        try {
            DB::table('order_product_variation')->insert([
                'order_id' => $id,
                'product_variation_id' => $request->variation_id,
                'count' => $request->count,
                'price' => $amount,
                'discount_price' => isset($product_variation->discount_price) ? $product_variation->discount_price : $brand_discount,
                'brand_discount' => $brand_discount
            ]);

            $product_variation->decrement('remainder', $request->count);

            unset($amount);

            Log::create([
                'admin_id' => auth()->user()->id,
                'model' => 'Order',
                'item_id' => $id,
                'action' => 'updated'
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response([
                'success' => false,
                'message' => 'Ошибка транзакции'
            ], 400);
        }

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

    public function new()
    {
        $orders = Order::where('is_deleted', 0)
            ->where('status', 'new')
            ->latest()
            ->with('user', 'productVariations', 'productVariations.warehouses');

        if (isset($_GET['date']) && $_GET['date'] != '') {
            $orders = $orders->whereDate('created_at', $_GET['date']);
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $orders = $orders->where('id', trim($_GET['search']))
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', trim('%' . $_GET['search'] . '%'));
                });
        }

        $orders = $orders->paginate(12);

        $search = $_GET['search'] ?? '';
        $date = $_GET['date'] ?? '';

        return view('app.orders.new', compact(
            'orders',
            'search',
            'date',
        ));
    }

    public function accepted()
    {
        $orders = Order::where('is_deleted', 0)
            ->where('status', 'accepted')
            ->latest()
            ->with('user', 'productVariations', 'productVariations.warehouses');

        if (isset($_GET['date']) && $_GET['date'] != '') {
            $orders = $orders->whereDate('created_at', $_GET['date']);
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $orders = $orders->where('id', trim($_GET['search']))
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', trim('%' . $_GET['search'] . '%'));
                });
        }

        $orders = $orders->paginate(12);

        $search = $_GET['search'] ?? '';
        $date = $_GET['date'] ?? '';

        return view('app.orders.accepted', compact(
            'orders',
            'search',
            'date',
        ));
    }

    public function collected()
    {
        $orders = Order::where('is_deleted', 0)
            ->where('status', 'collected')
            ->latest()
            ->with('user', 'productVariations', 'productVariations.warehouses');

        if (isset($_GET['date']) && $_GET['date'] != '') {
            $orders = $orders->whereDate('created_at', $_GET['date']);
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $orders = $orders->where('id', trim($_GET['search']))
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', trim('%' . $_GET['search'] . '%'));
                });
        }

        $orders = $orders->paginate(12);

        $search = $_GET['search'] ?? '';
        $date = $_GET['date'] ?? '';

        return view('app.orders.collected', compact(
            'orders',
            'search',
            'date',
        ));
    }

    public function on_the_way()
    {
        $orders = Order::where('is_deleted', 0)
            ->where('status', 'on_the_way')
            ->latest()
            ->with('user', 'productVariations', 'productVariations.warehouses');

        if (isset($_GET['date']) && $_GET['date'] != '') {
            $orders = $orders->whereDate('created_at', $_GET['date']);
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $orders = $orders->where('id', trim($_GET['search']))
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', trim('%' . $_GET['search'] . '%'));
                });
        }

        $orders = $orders->paginate(12);

        $search = $_GET['search'] ?? '';
        $date = $_GET['date'] ?? '';

        return view('app.orders.on_the_way', compact(
            'orders',
            'search',
            'date',
        ));
    }

    public function done()
    {
        $orders = Order::where('is_deleted', 0)
            ->where('status', 'done')
            ->latest()
            ->with('user', 'productVariations', 'productVariations.warehouses');

        if (isset($_GET['date']) && $_GET['date'] != '') {
            $orders = $orders->whereDate('created_at', $_GET['date']);
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $orders = $orders->where('id', trim($_GET['search']))
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', trim('%' . $_GET['search'] . '%'));
                });
        }

        $orders = $orders->paginate(12);

        $search = $_GET['search'] ?? '';
        $date = $_GET['date'] ?? '';

        return view('app.orders.done', compact(
            'orders',
            'search',
            'date',
        ));
    }

    public function returned()
    {
        $orders = Order::where('is_deleted', 0)
            ->where('status', 'returned')
            ->latest()
            ->with('user', 'productVariations', 'productVariations.warehouses');

        if (isset($_GET['date']) && $_GET['date'] != '') {
            $orders = $orders->whereDate('created_at', $_GET['date']);
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $orders = $orders->where('id', trim($_GET['search']))
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', trim('%' . $_GET['search'] . '%'));
                });
        }

        $orders = $orders->paginate(12);

        $search = $_GET['search'] ?? '';
        $date = $_GET['date'] ?? '';

        return view('app.orders.returned', compact(
            'orders',
            'search',
            'date',
        ));
    }

    public function cancelled()
    {
        $orders = Order::where('is_deleted', 0)
            ->where('status', 'cancelled')
            ->latest()
            ->with('user', 'productVariations', 'productVariations.warehouses');

        if (isset($_GET['date']) && $_GET['date'] != '') {
            $orders = $orders->whereDate('created_at', $_GET['date']);
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $orders = $orders->where('id', trim($_GET['search']))
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', trim('%' . $_GET['search'] . '%'));
                });
        }

        $orders = $orders->paginate(12);

        $search = $_GET['search'] ?? '';
        $date = $_GET['date'] ?? '';

        return view('app.orders.cancelled', compact(
            'orders',
            'search',
            'date',
        ));
    }

    public function order_to_venkom(Order $order) // Order $order
    {
        // $new_ip_address = '94.232.24.102'; // 06.03.2023
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
            ->put(
                $base_url.'/orders',
                $req
            );

        return $res->json();

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
}
