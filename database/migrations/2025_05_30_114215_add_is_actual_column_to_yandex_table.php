<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActualColumnToYandexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yandex_delivery_orders', function (Blueprint $table) {
            $table->boolean('is_actual')->default(0);
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
            $table->dropColumn('is_actual');
        });
    }
}
