<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Warehouse;
use App\Models\FargoHistory;

class FargoController extends Controller
{
    protected $username = 'd.mukhamedjanov@invema.uz';
    protected $password = 'Fargo6015';
    protected $base_url = 'https://prodapi.shipox.com/api';
    protected $token = '';

    public function login()
    {
        $res = Http::post($this->base_url.'/v1/customer/authenticate', [
            'username' => $this->username,
            'password' => $this->password,
        ]);

        $body = $res->json();

        if($body['status'] == 'success') $this->token = $body['data']['id_token'];
        else return response()->with([
            'success' => false,
            'message' => 'Server error'
        ], 400);
    }

    public function get_prices()
    {
        $this->login();

        $door_door_base_price = 0;
        $city_base_price = 0;

        // cena za 1kg posilki mejdu oblastyami
        $res = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->token
        ])->get($this->base_url.'/v2/customer/packages/prices/starting_from', [
            'from_latitude' => '41.33708107030719', // Tashkent
            'from_longitude' => '69.28459868877579',
            'to_latitude' => '39.65888231424179', // Samarqand
            'to_longitude' => '66.97565051290078',
            'dimensions.weight' => 1,
            'dimensions.unit' => 'METRIC',
            'to_country_id' => 234,
            'from_country_id' => 234,
            'logistic_type' => 'REGULAR'
        ]);

        $body = $res->json();

        if($body['status'] == 'success') {
            
            foreach($body['data']['list'] as $item) {
                if($item['courier_type']['type'] == 'DOOR_DOOR') {
                    $door_door_base_price = $item['price']['base'];
                }
            }

        } else {
            return response([
                'success' => false,
                'message' => 'Server error'
            ], 400);
        }

        // cena za 1kg posilki vnutri goroda
        $res = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->token
        ])->get($this->base_url.'/v2/customer/packages/prices/starting_from', [
            'from_latitude' => '41.33708107030719',
            'from_longitude' => '69.28459868877579',
            'to_latitude' => '41.3125192992969',
            'to_longitude' => '69.28166844209632',
            'dimensions.weight' => 1,
            'dimensions.unit' => 'METRIC',
            'to_country_id' => 234,
            'from_country_id' => 234,
            'logistic_type' => 'REGULAR'
        ]);

        $body = $res->json();

        if($body['status'] == 'success') {
            foreach($body['data']['list'] as $item) {
                if($item['courier_type']['type'] == 'CITY') $city_base_price = $item['price']['base'];
            }
        } else return response([
            'success' => false,
            'message' => 'Server error'
        ], 400);

        return compact(
            'city_base_price',
            'door_door_base_price'
        );
    }

    public function create_order(Order $order)
    {
        $this->login();

        $service_type = $order->region == 1 ? 'CITY' : 'DOOR_DOOR';
        $sender_phone_number = '+998997639696';

        $req = [];
        $req['sender_data']['address_type'] = 'business';
        $req['sender_data']['name'] = 'OKC.uz';
        $req['sender_data']['email'] = '';
        $req['sender_data']['apartment'] = '';
        $req['sender_data']['building'] = '';
        $req['sender_data']['street'] = '';
        $req['sender_data']['landmark'] = '';
        $req['sender_data']['phone'] = $sender_phone_number;
        $req['sender_data']['city']['id'] = '1216279901';
        $req['sender_data']['country']['id'] = '234';
        // 1 - mirza ulugbek, 2 - mirobod
        // $warehouse_id == 1 ? '1216494669' : '1216494668'
        $req['sender_data']['neighborhood']['id'] =  '1216494669';
        // $warehouse_id == 1 ? 'Mirzo Ulugbek' : 'Mirobod Tumani'
        $req['sender_data']['neighborhood']['name'] = 'Mirzo Ulugbek';

        $req['recipient_data']['address_type'] = 'residential';
        $req['recipient_data']['name'] = $order->name;
        $req['recipient_data']['apartment'] = '';
        $req['recipient_data']['building'] = '';
        $req['recipient_data']['street'] = '';
        if($order->region == 1) {
            $req['recipient_data']['city']['id'] = '1216279901';

            $req['recipient_data']['neighborhood']['id'] =  config('app.DISTRICTS')[$order->district - 1]['fargo_id'];
            $req['recipient_data']['neighborhood']['name'] =  config('app.DISTRICTS')[$order->district - 1]['title'];
        } else $req['recipient_data']['city']['id'] = config('app.DISTRICTS')[$order->district - 1]['fargo_id'];

        $req['recipient_data']['country']['id'] = '234';
        $req['recipient_data']['phone'] = $order->phone_number;
        $req['recipient_data']['landmark'] = $order->address;

        $req['dimensions']['weight'] = 1;
        $req['dimensions']['width'] = '';
        $req['dimensions']['length'] = '';
        $req['dimensions']['height'] = '';
        $req['dimensions']['unit'] = 'METRIC';
        $req['dimensions']['domestic'] = true;

        $req['package_type']['courier_type'] = $service_type;

        if($order->payment_method == 'cash') {
            $req['charge_items'][0]['paid'] = false;
            $req['charge_items'][0]['charge_type'] = 'cod';
            $req['charge_items'][0]['payer'] = 'recipient';
            $req['charge_items'][0]['charge'] = $order->amount / 100;
        }

        $req['recipient_not_available'] = 'do_not_deliver';
        $req['payment_type'] = 'credit_balance';
        $req['payer'] = 'sender';
        $req['parcel_value'] = ($order->payment_method == 'online' && $order->payment_card == 'click') ? $order->amount : $order->amount/100;
        $req['fragile'] = true;
        $req['force_create'] = true;
        $req['reference_id'] = $order->id;
        $req['note'] = ''; // nazvanie tovara
        $req['piece_count'] = $this->get_products_count($order);

        // otpravka zaprosa
        $res = Http::withHeaders(['Authorization' => 'Bearer '.$this->token])
            ->post($this->base_url.'/v2/customer/order', $req);

        $body = $res->json();

        if($body['status'] == 'success') return response([
            'success' => true,
            'message' => 'Добавлен в список Fargo'
        ], 200);
        else return response([
            'success' => false,
            'message' => 'Server error'
        ], 400);
    }

    public function get_products_count(Order $order)
    {
        if(!$order) return response([
            'success' => false,
            'message' => 'Нет заказ с такой ID'
        ]);

        $products_count = 0;

        foreach($order->productVariations as $item) {
            $products_count += $item->pivot->count;
        }

        return $products_count;
    }

    public function webhook(Request $request)
    {
        $data = $request->only(
            'order_number',
            'reference_id',
            'status',
            'note',
            'date',
            'customer_id',
            'driver'
        );

        FargoHistory::create($data);

        if($data['status'] == 'dispatched') {
            $order = Order::find($data['reference_id']);

            $order->update([
                'status' => 'on_the_way'
            ]);
        }

        if($data['status'] == 'delivered') {
            $order = Order::find($data['reference_id']);

            $order->update([
                'status' => 'done'
            ]);
        }

        return [
            'success' => true,
            'message' => ''
        ];
    }
}
