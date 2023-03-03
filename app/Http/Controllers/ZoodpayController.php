<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\ZoodpayTransaction;
use App\Models\ZoodpayHistory;

class ZoodpayController extends Controller
{
    // protected $base_url_sandbox = 'https://sandbox-api.zoodpay.com/v0';
    protected $base_url_sandbox = 'https://api.zoodpay.com/v0';
    protected $base_url_prod = 'https://api.zoodpay.com/v0';
    protected $merchant = 'bU8BK}Q9vzBs7k*';
    protected $secret = '7[G9d6sMRwKG)Dn)';
    protected $salt = '7&#UArbX';

    public function configuration()
    {
        $res = Http::withBasicAuth($this->merchant, $this->secret)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => '*/*'
            ])->post($this->base_url_sandbox.'/configuration', [
                'market_code' => 'UZ',
            ]);

        $body = $res->json();

        if(!isset($body['configuration'])) {
            return response([
                "configuration" => [
                    [
                        "min_limit" => 0,
                        "max_limit" => 0
                    ]
                ]
            ]);
        }

        return $body;
    }

    public function credit_balance()
    {
        $res = Http::withBasicAuth($this->merchant, $this->secret)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => '*/*'
            ])->post($this->base_url_sandbox.'/customer/credit/balance', [
                'market_code' => 'UZ',
                'customer_mobile' => '998125369413'
            ]);

        $body = $res->json();return response($body);
    }

    public function create_transaction(Order $order) // Order $order
    {
        // $order = Order::find(130);
        // if($order->payment_method == 'online' && $order->payment_card == 'click') {
        //     $order_amount_in_uzs = $order->amount;
        // } else {
        //     $order_amount_in_uzs = $order->amount / 100;
        // } 

        $order_amount_in_uzs = $order->amount;

        // if amount not in min and max limit
        $data = $this->configuration();

        $min_limit = $data['configuration'][0]['min_limit'];
        $max_limit = $data['configuration'][0]['max_limit'];
        
        if($order_amount_in_uzs < $min_limit || $order_amount_in_uzs > $max_limit) {
            return response([
                'success' => false,
                'message' => 'Сумма заказа больше max лимита или меньше min лимита'
            ], 400);
        }

        // products for response
        $products = $order->productVariations;
        $result = [];
        $counter = 0;
        foreach($products as $item) {
            foreach($item->product->categories as $category) {
                $result[$counter]['categories'][][0] = $category->title['ru'];
            }
            $result[$counter]['currency_code'] = 'UZS';
            $result[$counter]['name'] = $item->product->title['ru'];
            $result[$counter]['price'] = $item->pivot->discount_price ?? $item->pivot->price;
            $result[$counter]['quantity'] = $item->pivot->count;
            $result[$counter]['tax_amount'] = 0;
            $result[$counter]['discount_amount'] = 0;

            $counter++;
        }

        $res = Http::withBasicAuth($this->merchant, $this->secret)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => '*/*'
            ])->post($this->base_url_sandbox.'/transactions', [
                'customer' => [
                    'customer_email' => $order->email ?? '',
                    'customer_phone' => $order->phone_number,
                    'first_name' => $order->name,
                    'last_name' => ''
                ],
                'order' => [
                    "signature" => $this->signature($order->id, $order_amount_in_uzs),
                    "service_code" => "ZPI",
                    "market_code" => "UZ",
                    "merchant_reference_no" => strval($order->id),
                    "currency" => "UZS",
                    "amount" => $order_amount_in_uzs,
                    "discount_amount" => 0,
                    "shipping_amount" => 0,
                    "tax_amount" => 0,
                    "lang" => "ru"
                ],
                'billing' => [
                    'name' => $order->name,
                    'phone_number' => $order->phone_number,
                    'country_code' => "UZ",
                    'address_line1' => $order->address,
                    'city' => config('app.REGIONS')[$order->region - 1]['uz'],
                    'zipcode' => $order->postal_code ?? '100010',
                    'state' => config('app.DISTRICTS')[$order->district - 1]['title']
                ],
                "shipping" => [
                    'name' => $order->name,
                    'phone_number' => $order->phone_number,
                    'country_code' => 'UZ',
                    'address_line1' => $order->address,
                    'city' => config('app.REGIONS')[$order->region - 1]['uz'],
                    'zipcode' => $order->postal_code ?? '100010',
                    'state' => config('app.DISTRICTS')[$order->district - 1]['title']
                ],
                "callbacks" => [
                    "success_url" => "https://admin.okc.uz/zoodpay/ipn",
                    "error_url" => "https://admin.okc.uz/zoodpay/ipn",
                    "ipn_url" => "https://admin.okc.uz/zoodpay/ipn",
                    "refund_url" => ""
                ],
                "items" => $result
            ]);

        $body = $res->json();

        if(isset($body['signature'])) {

            ZoodpayTransaction::updateOrCreate([
                'transaction_id' => $body['transaction_id'],
            ],[
                'order_id' => $order->id,
                'customer_phone_number' => $order->phone_number,
                'payment_url' => $body['payment_url'],
                'signature' => $body['signature'],
                'amount' => $order_amount_in_uzs
            ]);

            return $body['payment_url'];

        }
    }

    public function ipn(Request $request)
    {
        $data = $request->only(
            'transaction_id',
            'status',
            'amount',
            'signature',
            'merchant_order_reference',
            'errorMessage'
        );
        $data['order_id'] = $data['merchant_order_reference'];

        ZoodpayHistory::create($data);

        if($data['errorMessage'] != '') {
            return redirect('https://okc.uz/zoodpay/error');    
        }

        return redirect('https://okc.uz/zoodpay/succes');

    }

    public function signature($merchant_reference_no, $amount)
    {
        return hash('sha512', $this->merchant.'|'.$merchant_reference_no.'|'.$amount.'|UZS|UZ|'.$this->salt);
    }
}
