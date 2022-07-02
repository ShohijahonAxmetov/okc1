<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
            $table->string('color_id')->nullable();
            $table->string('venkon_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('remainder')->default(0);
            $table->bigInteger('price');
            $table->bigInteger('discount_price')->nullable();
            $table->boolean('is_default')->default(0);
            $table->boolean('is_available')->default(1);
            $table->boolean('with_discount')->default(0);
            $table->boolean('is_active')->default(0);
            $table->string('slug')->unique();
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
        Schema::dropIfExists('product_variations');
    }
}
