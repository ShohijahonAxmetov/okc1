<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('venkon_id')->nullable();
            $table->enum('discount_type', ['order', 'brand', 'product', 'category']);
            $table->enum('amount_type', ['percent', 'fixed']);
            $table->string('venkon_brand_id')->nullable();
            $table->string('venkon_category_id')->nullable();
            $table->string('venkon_product_id')->nullable();
            $table->string('from_amount')->nullable();
            $table->string('to_amount')->nullable();
            $table->string('discount');
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('discounts');
    }
}
