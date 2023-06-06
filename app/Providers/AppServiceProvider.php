<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        if(Schema::hasTable('orders')) {
            // orders counts
            $new_orders_count = \App\Models\Order::where('status', 'new')->count() ?? null;
            $accepted_orders_count = \App\Models\Order::where('status', 'accepted')->count() ?? null;
            $cancelled_orders_count = \App\Models\Order::where('status', 'cancelled')->count() ?? null;
            $collected_orders_count = \App\Models\Order::where('status', 'collected')->count() ?? null;
            $on_the_way_orders_count = \App\Models\Order::where('status', 'on_the_way')->count() ?? null;
            $done_orders_count = \App\Models\Order::where('status', 'done')->count() ?? null;
            $returned_orders_count = \App\Models\Order::where('status', 'returned')->count() ?? null;
            View::share('orders_count', [
                'new' => $new_orders_count,
                'accepted' => $accepted_orders_count,
                'cancelled' => $cancelled_orders_count,
                'collected' => $collected_orders_count,
                'on_the_way' => $on_the_way_orders_count,
                'done' => $done_orders_count,
                'returned' => $returned_orders_count,
            ]);
        }
    }
}
