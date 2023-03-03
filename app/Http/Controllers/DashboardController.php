<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProductVariation;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $this_day_orders = Order::latest()->where('created_at', '>', date('Y-m-d', time()))
            ->with('productVariations', 'productVariations.product');
        if(isset($_GET['status']) && $_GET['status'] != '') {
            $this_day_orders = $this_day_orders->where('status', $_GET['status']);
        }
        $this_day_orders = $this_day_orders->get();
        $stock = ProductVariation::whereHas('product', function($q) {
                $q->where('is_active', 1);
            })
            ->with('product')
            ->get();

        $this_month_orders_count = Order::whereMonth('created_at', date('m', time()))
            ->count();
        $this_month_new_clients_count = User::whereMonth('created_at', date('m', time()))
            ->count();
        $this_month_new_clients = User::whereMonth('created_at', date('m', time()))
            ->latest()
            ->take(8)
            ->get();
        $this_month_orders = Order::whereMonth('created_at', date('m', time()))
            ->where('status', 'done')
            ->get();

        $this_month_amount = 0;
        // foreach($this_month_orders as $order) {
        //     if($order->payment_method == 'cash') {
        //         $this_month_amount += $order->amount / 100;
        //     } else {
        //         if($order->payment_card == 'payme') {
        //             $this_month_amount += $order->amount / 100;
        //         } elseif($order->payment_card == 'click') {
        //             $this_month_amount += $order->amount;
        //         }
        //     }
        // }

        $orders_for_chart = [];
        for($i=1; $i<32; $i++) {
            $orders_for_chart['day'][] = $i;
            $total = 0;
            $orders = Order::whereMonth('created_at', date('m', time()))
                ->whereDay('created_at', $i)
                ->get();
            foreach($orders as $order) {
                if($order->payment_method == 'cash') {
                    $total += $order->amount / 100;
                } else {
                    if($order->payment_card == 'payme') {
                        $total += $order->amount / 100;
                    } elseif($order->payment_card == 'click') {
                        $total += $order->amount;
                    }
                }
            }
            $orders_for_chart['amount'][] = $total;
        }

        $status = $_GET['status'] ?? '';

        return view('app.dashboard', compact(
            'this_day_orders',
            'stock',
            'this_month_orders_count',
            'this_month_new_clients_count',
            'this_month_amount',
            'orders_for_chart',
            'this_month_new_clients',
            'status'
        ));
    }
}
