<?php

use Illuminate\Database\Seeder;

class TimeframeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        if(!\App\Models\Order\Timeframe::where('name', '=', '8:00 AM - 12:00 AM')->first()){
            \App\Models\Order\Timeframe::create([
                'name' => '8:00 AM - 12:00 AM',
                'order' => 1,
                'enabled' => 1,
            ]);
        }

        if(!\App\Models\Order\Timeframe::where('name', '=', '12:00 AM - 16:00 PM')->first()){
            \App\Models\Order\Timeframe::create([
                'name' => '12:00 AM - 16:00 PM',
                'order' => 2,
                'enabled' => 1,
            ]);
        }

        if(!\App\Models\Order\Timeframe::where('name', '=', '16:00 PM - 20:00 PM')->first()){
            \App\Models\Order\Timeframe::create([
                'name' => '16:00 PM - 20:00 PM',
                'order' => 3,
                'enabled' => 1,
            ]);
        }
        if(!\App\Models\Order\Timeframe::where('name', '=', '20:00 PM +')->first()){
            \App\Models\Order\Timeframe::create([
                'name' => '20:00 PM +',
                'order' => 4,
                'enabled' => 1,
            ]);
        }
    }
}
