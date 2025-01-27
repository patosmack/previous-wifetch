<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable();
            $table->foreignId('user_id');
            $table->string('name');
            $table->string('friendly_url');

            $table->string('external_udid')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();

            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();

            $table->foreignId('country_id')->nullable();
            $table->foreignId('parish_id')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();

            $table->string('notification_email')->nullable();
            $table->string('notification_phone')->nullable();

            $table->double('delivery_fee')->default(20);
            $table->double('service_fee')->default(3);

            $table->boolean('enabled')->default(0);
            $table->boolean('featured')->default(0);
            $table->boolean('allow_custom_items')->default(0);
            $table->enum('status', ['pending', 'approved', 'cancelled', 'rejected'])->default('pending');
            $table->timestamp('status_updated')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
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
        Schema::dropIfExists('merchant_infos');
    }
}
