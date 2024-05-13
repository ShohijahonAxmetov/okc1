<?php

namespace App\Http\Controllers\web;

//Models
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\Warehouse;

//Controllers
use App\Http\Controllers\Controller;
use App\Http\Controllers\FargoController;
use App\Http\Controllers\ZoodpayController;

// Laravel
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        return response([
            'message' => 'Введутся технические работы, можете позвонить и заказать!',
            'success' => false
        ], 400);
        // esli vibran zabrat iz magazina, to doljen i vibiratsya magazin
        if($request->with_delivery == 0) {
            $validator = Validator::make($request->all(), [
                'shop' => 'required'
            ]);
            if ($validator->fails()) return response([
                'message' => $validator->errors()
            ], 422);
        }

//        dd($request->all());

        /* esli netu dostatochnogo kolichestva produktov v magazine */
        $check_product_sufficiency = $this->check_product_sufficiency($request->all());
        if(!$check_product_sufficiency) return response([
            'message' => 'Нет такого кол-ва товаров',
            'success' => false
        ], 400);

//        dd($check_product_sufficiency);

        // preobrazovanie kollekcii v massiv
        $data = $request->all();

        // validaciya dannix
        $validator = Validator::make($data, [
            'phone_number' => 'required|max:255',
            'with_delivery' => 'required',
            'payment_method' => 'required|in:cash,online,card',
            'payment_card' => 'nullable|in:payme,click,zoodpay',
            'delivery_type' => [Rule::requiredIf($data['with_delivery']), 'integer', 'in:1,2'], //1-fargo,2-yandex
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

//        dd(1);

        // podgotovka dannix dlya zapisi
        $data['product_variations'] = json_decode($data['product_variations'], true);
        $data['phone_number'] = preg_replace('/[^0-9]/', '', $data['phone_number']);
        $amount = 0;
        foreach ($data['product_variations'] as $variation) {
            $product_variation = ProductVariation::find($variation['id']);

            if (isset($product_variation->discount_price)) $amount += (preg_replace('/[^0-9]/', '', $product_variation->discount_price) * 100) * $variation['count'];
            else $amount += (preg_replace('/[^0-9]/', '', $product_variation->price) * 100) * $variation['count'];
        }

        // esli s dostavkoy pribavim summu dostavki
        if ($data['with_delivery'] == 1) {
            if ($data['delivery_type'] == 1) {
                // poluchat ceni dostavki s Fargo
                if (Cache::has('fargo_prices')) $delivery_prices = Cache::get('fargo_prices');
                else {
                    $fargo_prices = new FargoController;
                    $delivery_prices = $fargo_prices->get_prices();

                    Cache::put('fargo_prices', $delivery_prices, 60*60*24);
                }
                $delivery_price = $request->region == 1 ? $delivery_prices['city_base_price'] : $delivery_prices['door_door_base_price'];
                $free_delivery_from = 700000;
            } else {
                $delivery_price = 15000;
            }
            $data['delivery_price'] = $delivery_price;
            $amount += $delivery_price * 100;
        }

//        dd($amount);

        if ($data['payment_method'] == 'cash') $data['amount'] = $amount;
        else {
            if ($data['payment_card'] == 'payme') $data['amount'] = $amount;
            else if ($data['payment_card'] == 'click') $data['amount'] = $amount / 100;
            else if ($data['payment_card'] == 'zoodpay') $data['amount'] = $amount / 100; // this changed
            else return response([
                'success' => false,
                'message' => 'Payment system not found'
            ]);
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

            if(!$warehouse_id) return response([
                'success' => false,
                'message' => 'Не выбран склад для'
            ], 400);

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
//            $this->sendMessageToTelegram($data, $order);

            // send data to 1c
            $res = $this->order_to_venkom($order);
            if(!$res['success']) return response([
                'success' => false,
                'message' => $res['error']
            ]);

            DB::commit();

            if ($data['payment_method'] != 'cash') {

                if ($data['payment_card'] == 'click') return response([
                    'url' => url('') . '/api/pay/click/' . $order->id . '/' . ($order->amount),
                    'with_url' => true,
                    'success' => true
                ]);
                else if ($data['payment_card'] == 'payme') return response([
                    'url' => url('') . '/api/pay/payme/' . $order->id . '/' . ($order->amount / 100),
                    'with_url' => true,
                    'success' => true
                ]);
                else if ($data['payment_card'] == 'zoodpay') {

                    $zoodpay = new ZoodpayController;
                    $payment_url = $zoodpay->create_transaction($order);

                    return response([
                        'url' => $payment_url,
                        'with_url' => true,
                        'success' => true
                    ]);
                } else return response([
                    'success' => false,
                    'message' => 'Payment system not found'
                ]);
            } else return response([
                'with_url' => false,
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'message' => 'Error',
                'success' => false
            ], 400);
        }
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

                if($record) $records_count ++;
            }

            if($records_count == count($data['product_variations'])) $warehouse->active = true;
            $warehouses_for_res[] = $warehouse;
        }

        foreach($warehouses_for_res as $item) {
            if($item['active'] == true) return true;
        }
        return false;
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
        if ($order->payment_method == 'cash') $total = $order->amount / 100;
        else {
            if ($order->payment_card == 'payme') $total = $order->amount /100;
            else if ($order->payment_card == 'click') $total = $order->amount;
            else if ($order->payment_card == 'zoodpay') $total = $order->amount;
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
        $res = Http::withBasicAuth($login, $password)->post($base_url.'/orders', $req);

        return $res;
    }

    function sendMessageToTelegram($data, $order): void
    {
        $total = 0;

        if ($data['payment_method'] == 'cash') $total = $order->amount / 100;
        else {
            if ($data['payment_card'] == 'payme') $total = $order->amount /100;
            else if ($data['payment_card'] == 'click') $total = $order->amount;
            else if ($data['payment_card'] == 'zoodpay') $total = $order->amount;
        }

        file_get_contents('https://api.telegram.org/bot5575336376:AAGG1V_yLba7eT3H9WoVFvw_-bJE2Ll5oK8/sendMessage?parse_mode=HTML&chat_id=-1001746315950&text=' .
            'Новый заказ' .

            '%0A%0A<b>Заказ ID:</b> ' . $order->id .

            '%0A%0A<b>Имя:</b> ' . $order->name .
            "%0A<b>Номер телефона:</b> " . $order->phone_number .  // %2B - this is +

            '%0A%0A<b>Сумма:</b> ' . $total . ' сум' .

            "%0A%0A<a href='https://admin.okc.uz/dashboard/orders'>" . 'Перейти на сайт' . "</a>");
    }
}
