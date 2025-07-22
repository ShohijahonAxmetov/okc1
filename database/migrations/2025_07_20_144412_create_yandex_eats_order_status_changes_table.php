<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYandexEatsOrderStatusChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yandex_eats_order_status_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->references('id')->on('yandex_eats_orders')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['NEW', 'ACCEPTED_BY_RESTAURANT', 'COOKING', 'READY', 'TAKEN_BY_COURIER', 'DELIVERED', 'CANCELLED']);
            $table->string('comment')->nullable();
            $table->integer('packages_count')->nullable();
            $table->string('reason')->nullable();
            $table->string('platform')->nullable();
            $table->text('attributes')->nullable();
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
        Schema::dropIfExists('yandex_eats_order_status_changes');
    }
}
