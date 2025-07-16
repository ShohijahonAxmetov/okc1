<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumntToLoadingPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loading_points', function (Blueprint $table) {
            $table->text('address')->nullable();
            $table->text('comments')->nullable()->comment('Коммент для водителя');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loading_points', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('comments');
        });
    }
}
