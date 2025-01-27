<?php

use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_method = \App\Models\Order\PaymentMethod::where('slug', '=', 'credit_card')->first();

        if(!$payment_method){
            $payment_method = new \App\Models\Order\PaymentMethod();
            $payment_method->slug = 'credit_card';
            $payment_method->name = 'Credit Card';
            $payment_method->description = "<h4>As soon as we process your order</h4><p>We will send an email including the payment link for you to complete the payment</p>";
            $payment_method->enabled = 1;
            $payment_method->save();
        }

    }
}
