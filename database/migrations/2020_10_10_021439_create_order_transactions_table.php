<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable();
            $table->foreignId('transaction_payment_method_id')->nullable();
            $table->decimal('transaction_approved_total')->nullable();
            $table->decimal('transaction_total')->nullable();
            $table->decimal('transaction_shipping')->nullable();
            $table->decimal('transaction_handling_cost')->nullable();
            $table->string('transaction_accounting_reference')->nullable();
            $table->string('transaction_accounting_file')->nullable();
            $table->text('transaction_id')->nullable();
            $table->text('transaction_info')->nullable();
            $table->text('transaction_extra')->nullable();
            $table->text('transaction_url')->nullable();
            $table->text('transaction_description')->nullable();
            $table->enum('transaction_status', ['pending', 'pending_transaction_email', 'approved', 'rejected', 'refunded', 'correction_requested', 'partially_refunded', 'canceled'])->default('pending');
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
        Schema::dropIfExists('order_transactions');
    }
}
