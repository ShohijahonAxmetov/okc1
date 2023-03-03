<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('amount');
            $table->string('name')->nullable();
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->integer('region')->nullable();
            $table->string('district')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('address')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('with_delivery');
            $table->enum('delivery_method', ['bts', 'delivery'])->nullable();
            $table->enum('payment_method', ['cash', 'online', 'card']);
            $table->enum('payment_card', ['payme', 'click', 'zoodpay']);
            $table->enum('status', ['new', 'collected', 'on_the_way', 'returned', 'done', 'cancelled', 'accepted'])->default('new');
            $table->boolean('is_deleted')->default(0);
            $table->boolean('is_payed')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
