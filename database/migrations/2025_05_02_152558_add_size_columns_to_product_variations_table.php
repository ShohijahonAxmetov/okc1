<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSizeColumnsToProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_variations', function (Blueprint $table) {
            $table->float('height', 8, 2)->nullable();
            $table->float('length', 8, 2)->nullable();
            $table->float('width', 8, 2)->nullable();
            $table->float('weight', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variations', function (Blueprint $table) {
            $table->dropColumn('height');
            $table->dropColumn('length');
            $table->dropColumn('width');
            $table->dropColumn('weight');
        });
    }
}
