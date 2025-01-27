<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->enum('type', ['driver', 'fetcher', 'both'])->default('both');
            $table->string('avatar')->nullable();
            $table->string('external_udid')->nullable();
            $table->string('license')->nullable();
            $table->foreignId('country_id')->nullable();
            $table->foreignId('parish_id')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('vehicle_plate')->nullable();
            $table->string('vehicle_brand')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_year')->nullable();
            $table->string('vehicle_color')->nullable();

            $table->string('license_image_front')->nullable();
            $table->string('license_image_back')->nullable();

            $table->double('delivery_fee')->default(0);
            $table->double('service_fee')->default(0);

            $table->boolean('enabled')->default(0);
            $table->enum('status', ['pending', 'approved', 'cancelled', 'rejected'])->default('pending');
            $table->timestamp('status_updated')->useCurrent();

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
        Schema::dropIfExists('driver_infos');
    }
}
