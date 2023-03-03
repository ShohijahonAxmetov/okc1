<?php
    
    $order = \App\Models\Order::find($transaction->transactionable_id);
    $order->update([
        'is_payed' => 1
    ]);

    if($order->status == 'new') {
        // obnovit status
        $order->update([
            'status' => 'accepted'
        ]);

        // noviy zakaz Fargo
        if($order->payment_method == 'online' && $order->with_delivery == 1) {
            $fargo = new \App\Http\Controllers\FargoController;

            $fargo->create_order($order);
        }
    }



    // send to 1c
    $base_url = 'http://213.230.65.189/UT_NewClean/hs/invema_API';
    $login = 'Venkon';
    $password = 'overlord';

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
    $res = \Illuminate\Support\Facades\Http::withBasicAuth($login, $password)
        ->put(
            $base_url.'/orders',
            $req
        );

    return $res->json();