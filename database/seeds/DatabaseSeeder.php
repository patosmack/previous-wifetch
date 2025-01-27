<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LocationSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(TimeframeSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(BannerSeeder::class);

    }
}
