<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockedIpAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocked_ip_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('blocked_at');
            $table->boolean('status')->comment('1-active, 0-inactive (blokdan ochilgan)');
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
        Schema::dropIfExists('blocked_ip_addresses');
    }
}
