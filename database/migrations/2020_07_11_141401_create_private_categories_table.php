<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('private_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('private_category_id')->nullable();
            $table->foreignId('merchant_info_id');
            $table->string('name');
            $table->boolean('enabled')->default(0);
            $table->timestamps();
            $table->foreign('private_category_id')->references('id')->on('private_categories')->onDelete('set null');
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
        Schema::dropIfExists('private_categories');
    }
}
