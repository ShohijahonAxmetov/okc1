<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCharacteristicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_characteristics', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('product_variation_id')->constrained();
            $table->bigInteger('market_characteristic_id');
            $table->bigInteger('market_category_id');
            $table->text('name');
            $table->string('type');
            $table->text('description')->nullable();
            $table->text('values')->nullable();
            $table->boolean('required');
            $table->boolean('filtering');
            $table->boolean('distinctive');
            $table->boolean('multivalue');
            $table->boolean('allowCustomValues');
            $table->text('constraints')->nullable();
            // $table->text('unit');

            // $table->text('result');
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
        Schema::dropIfExists('product_characteristics');
    }
}
