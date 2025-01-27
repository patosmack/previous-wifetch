<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_info_id')->nullable();
            $table->foreignId('private_category_id')->nullable();
            $table->string('name');
            $table->string('friendly_url');
            $table->string('external_udid')->nullable();
            $table->string('image')->nullable();
            $table->double('price')->default(0);
            $table->double('discount')->default(0);
            $table->integer('stock')->default(0);
            $table->boolean('always_on_stock')->default(0);
            $table->integer('max_quantity')->default(0);
            $table->text('description')->nullable();
            $table->string('unit')->nullable();
            $table->longText('searchable')->nullable();
            $table->boolean('enabled')->default(0);
            $table->boolean('featured')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('merchant_info_id')->references('id')->on('merchant_infos')->onDelete('set null');
            $table->foreign('private_category_id')->references('id')->on('private_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
