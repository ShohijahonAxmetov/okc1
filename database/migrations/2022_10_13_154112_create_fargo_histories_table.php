<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFargoHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fargo_histories', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->bigInteger('reference_id');
            $table->string('status');
            $table->text('note')->nullable();
            $table->string('date');
            $table->string('customer_id');
            $table->text('driver')->nullable();
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
        Schema::dropIfExists('fargo_histories');
    }
}
