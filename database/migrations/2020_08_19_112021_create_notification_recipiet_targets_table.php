<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationRecipietTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_recipiet_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_recipiet_id')->nullable();
            $table->string('type');
            $table->string('target');
            $table->timestamps();
            $table->foreign('notification_recipiet_id')->references('id')->on('notification_recipiets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_recipiet_targets');
    }
}
