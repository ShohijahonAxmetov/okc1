<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClaimColumnsForYandexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yandex_delivery_orders', function (Blueprint $table) {
            $table->text('claim_res')->nullable()->comment('Ответ от Яндекс');
            $table->integer('claim_status')->nullable()->comment('Статус запроса');
            $table->string('claim_id')->nullable()->comment('ID заявки, полученный на этапе создания заявки');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yandex_delivery_orders', function (Blueprint $table) {
            $table->dropColumn('claim_res');
            $table->dropColumn('claim_status');
            $table->dropColumn('claim_id');
        });
    }
}
