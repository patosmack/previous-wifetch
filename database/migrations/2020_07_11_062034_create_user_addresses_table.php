<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name')->nullable();
            $table->foreignId('country_id')->nullable();
            $table->foreignId('parish_id')->nullable();
            $table->text('address')->nullable();
            $table->text('secondary_address')->nullable();
            $table->string('phone')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('current')->default(0);
            $table->boolean('enabled')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('parish_id')->references('id')->on('parishes')->onDelete('set null');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
}
