<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('how_to_use')->after('desc')->nullable();
            $table->string('rating')->after('how_to_use')->default(0)->nullable();
            $table->integer('number_of_ratings')->after('rating')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('how_to_use');
            $table->dropColumn('rating');
            $table->dropColumn('number_of_ratings');
        });
    }
}
