<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYandexEatsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yandex_eats_orders', function (Blueprint $table) {
            $table->id();
            $table->text('brand')->nullable();
            $table->text('comment');
            $table->text('delivery_info');
            $table->string('discriminator');
            $table->string('eats_id');
            $table->text('items');
            $table->text('payment_info');
            $table->text('platform')->nullable();
            $table->string('restaurant_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yandex_eats_orders');
    }
}
