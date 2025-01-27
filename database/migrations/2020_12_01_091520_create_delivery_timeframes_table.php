<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryTimeframesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_timeframes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_info_id');
            $table->string('name');
            $table->unsignedSmallInteger('order')->default(0);
            $table->boolean('enabled')->default(0);
            $table->timestamps();
            $table->foreign('merchant_info_id')->references('id')->on('merchant_infos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_timeframes');
    }
}
