<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('merchant_info_id')->nullable();
            $table->string('description')->nullable();
            $table->string('file_name')->nullable();
            $table->string('udid')->nullable();
            $table->enum('status',['pending', 'processing', 'should_process', 'processed', 'failed'])->default('pending');
            $table->string('status_message')->nullable();
            $table->boolean('message_sent')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('merchant_info_id')->references('id')->on('merchant_infos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_histories');
    }
}
