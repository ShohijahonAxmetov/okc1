<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoadingPointColumnToYOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yandex_delivery_orders', function (Blueprint $table) {
            $table->foreignId('loading_point_id')->nullable()->constrained()->nullOnDelete()->nullOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yandex_delivery_orders', function (Blueprint $table) {
            $table->dropColumn('loading_point_id');
        });
    }
}
