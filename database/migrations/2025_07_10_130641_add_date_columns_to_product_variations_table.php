<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateColumnsToProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_variations', function (Blueprint $table) {
            $table->text('expiration_date')->nullable();
            $table->text('expiration_date_comment')->nullable();
            $table->text('service_life')->nullable();
            $table->text('service_life_comment')->nullable();
            $table->text('warranty_period')->nullable();
            $table->text('warranty_period_comment')->nullable();
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
            $table->dropColumn('expiration_date');
            $table->dropColumn('expiration_date_comment');
            $table->dropColumn('service_life');
            $table->dropColumn('service_life_comment');
            $table->dropColumn('warranty_period');
            $table->dropColumn('warranty_period_comment');
        });
    }
}
