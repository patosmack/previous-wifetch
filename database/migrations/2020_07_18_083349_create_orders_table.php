<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('user_address_id')->nullable();

            $table->foreignId('discount_id')->nullable();

            $table->foreignId('merchant_id')->nullable();

            $table->string('order_name')->nullable();
            $table->string('order_last_name')->nullable();
            $table->string('order_email')->nullable();
            $table->string('order_home_phone')->nullable();
            $table->string('order_mobile_phone')->nullable();
            $table->text('order_comment')->nullable();

            $table->foreignId('delivery_driver_id')->nullable();

            $table->foreignId('delivery_country_id')->nullable();
            $table->string('delivery_country')->nullable();

            $table->foreignId('delivery_parish_id')->nullable();
            $table->string('delivery_parish')->nullable();


            $table->string('delivery_address')->nullable();
            $table->string('delivery_secondary_address')->nullable();
            $table->string('delivery_lat')->nullable();
            $table->string('delivery_lon')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->string('delivery_instructions')->nullable();
            $table->date('delivery_date')->nullable();

            $table->foreignId('delivery_timeframe_id')->nullable();
            $table->string('delivery_timeframe')->nullable();

            $table->double('delivery_cost')->default(0);
            $table->text('tracking_code')->nullable();

            $table->foreignId('transaction_payment_method_id')->nullable();
            $table->decimal('transaction_approved_total')->nullable();
            $table->decimal('transaction_total')->nullable();
            $table->decimal('transaction_shipping')->nullable();
            $table->decimal('transaction_handling_cost')->nullable();
            $table->string('transaction_accounting_reference')->nullable();
            $table->string('transaction_accounting_file')->nullable();
            $table->enum('transaction_status', ['pending', 'pending_transaction_email', 'approved', 'rejected', 'refunded', 'correction_requested', 'partially_refunded', 'canceled'])->default('pending');
            $table->text('transaction_id')->nullable();
            $table->text('transaction_info')->nullable();
            $table->text('transaction_extra')->nullable();
            $table->text('transaction_url')->nullable();

//            $table->foreignId('append_transaction_payment_method_id')->nullable();
//            $table->decimal('append_transaction_approved_total')->nullable();
//            $table->decimal('append_transaction_total')->nullable();
//            $table->decimal('append_transaction_shipping')->nullable();
//            $table->decimal('append_transaction_handling_cost')->nullable();
//            $table->string('append_transaction_accounting_reference')->nullable();
//            $table->string('append_transaction_accounting_file')->nullable();
//            $table->enum('append_transaction_status', ['pending', 'pending_transaction_email', 'approved', 'rejected', 'canceled'])->nullable();
//            $table->text('append_transaction_id')->nullable();
//            $table->text('append_transaction_info')->nullable();
//            $table->text('append_transaction_extra')->nullable();
//            $table->text('append_transaction_url')->nullable();
//
//            $table->foreignId('refund_transaction_payment_method_id')->nullable();
//            $table->decimal('refund_transaction_approved_total')->nullable();
//            $table->decimal('refund_transaction_total')->nullable();
//            $table->decimal('refund_transaction_shipping')->nullable();
//            $table->decimal('refund_transaction_handling_cost')->nullable();
//            $table->string('refund_transaction_accounting_reference')->nullable();
//            $table->string('refund_transaction_accounting_file')->nullable();
//            $table->enum('refund_transaction_status', ['pending', 'pending_transaction_email', 'rejected', 'refunded', 'partially_refunded', 'canceled'])->nullable();
//            $table->text('refund_transaction_id')->nullable();
//            $table->text('refund_transaction_info')->nullable();
//            $table->text('refund_transaction_extra')->nullable();
//            $table->text('refund_transaction_url')->nullable();


//            $table->enum('order_status', [
//                'pending', 'processing', 'approved', 'rejected', 'canceled', 'completed',
//            ])->default('pending');
//
//
//            $table->enum('fetcher_status', [
//                'ready_for_process', 'processing', 'finding_fetcher', 'fetching_order_items', 'packing_order', 'ready_for_pickup'
//            ])->nullable();
//
//            $table->enum('delivery_status', [
//                'ready_for_delivery', 'finding_delivery', 'delivery_on_place','collected_by_delivery', 'transit_to_pickup', 'transit_to_destination', 'near_destination', 'delivered',
//            ])->nullable();

            $table->enum('status', [
                'pending', 'waiting_for_payment', 'waiting_for_price', 'payment_approved', 'payment_rejected', 'payment_refunded', 'canceled', 'completed',
                'ready_to_fetch', 'finding_fetcher', 'fetching_order_items', 'ready_for_pickup',
                'ready_for_delivery','finding_delivery','delivery_on_place','collected_by_delivery',
                'transit_to_pickup','transit_to_destination','near_destination','delivered'
            ])->default('pending');

            $table->softDeletes();

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('merchant_id')->references('id')->on('merchant_infos')->onDelete('cascade');


            $table->foreign('transaction_payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
//            $table->foreign('append_transaction_payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
//            $table->foreign('refund_transaction_payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
            $table->foreign('user_address_id')->references('id')->on('user_addresses')->onDelete('set null');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('set null');
            $table->foreign('delivery_driver_id')->references('id')->on('driver_infos')->onDelete('set null');
            $table->foreign('delivery_country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('delivery_parish_id')->references('id')->on('parishes')->onDelete('set null');
            $table->foreign('delivery_timeframe_id')->references('id')->on('timeframes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
