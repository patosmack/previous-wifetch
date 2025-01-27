<?php

use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!$barbados = \App\Models\Location\Country::where('name', '=', 'Barbados')->first()){
            $barbados = \App\Models\Location\Country::create([
                'name' => 'Barbados',
                'iso' => 'bb',
                'iso3' => 'brb',
                'enabled' => 1,
            ]);
        }

        if($barbados){
            if(!\App\Models\Location\Parish::where('country_id', '=', $barbados->id)->where('name', '=', 'Christ Church')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $barbados->id,
                    'name' => 'Christ Church',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $barbados->id)->where('name', '=', 'St Philip')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $barbados->id,
                    'name' => 'St Philip',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $barbados->id)->where('name', '=', 'St Michael')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $barbados->id,
                    'name' => 'St Michael',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $barbados->id)->where('name', '=', 'St George')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $barbados->id,
                    'name' => 'St George',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $barbados->id)->where('name', '=', 'St John')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $barbados->id,
                    'name' => 'St John',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $barbados->id)->where('name', '=', 'St James')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $barbados->id,
                    'name' => 'St James',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $barbados->id)->where('name', '=', 'St Thomas')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $barbados->id,
                    'name' => 'St Thomas',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $barbados->id)->where('name', '=', 'St Joseph')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $barbados->id,
                    'name' => 'St Joseph',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $barbados->id)->where('name', '=', 'St Peter')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $barbados->id,
                    'name' => 'St Peter',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $barbados->id)->where('name', '=', 'St Andrew')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $barbados->id,
                    'name' => 'St Andrew',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $barbados->id)->where('name', '=', 'St Lucy')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $barbados->id,
                    'name' => 'St Lucy',
                    'enabled' => 1,
                ]);
            }

        }


        if(!$trinidad = \App\Models\Location\Country::where('name', '=', 'Trinidad')->first()){
           $trinidad = \App\Models\Location\Country::create([
                'name' => 'Trinidad',
                'iso' => 'tt',
                'iso3' => 'tto',
                'enabled' => 1,
            ]);
        }

        if($trinidad){
            if(!\App\Models\Location\Parish::where('country_id', '=', $trinidad->id)->where('name', '=', 'Caroni')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $trinidad->id,
                    'name' => 'Caroni',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $trinidad->id)->where('name', '=', 'Mayaro')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $trinidad->id,
                    'name' => 'Mayaro',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $trinidad->id)->where('name', '=', 'Nariva')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $trinidad->id,
                    'name' => 'Nariva',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $trinidad->id)->where('name', '=', 'Saint Andrew')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $trinidad->id,
                    'name' => 'Saint Andrew',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $trinidad->id)->where('name', '=', 'Saint David')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $trinidad->id,
                    'name' => 'Saint David',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $trinidad->id)->where('name', '=', 'Saint George')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $trinidad->id,
                    'name' => 'Saint George',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $trinidad->id)->where('name', '=', 'Saint Patrick')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $trinidad->id,
                    'name' => 'Saint Patrick',
                    'enabled' => 1,
                ]);
            }

            if(!\App\Models\Location\Parish::where('country_id', '=', $trinidad->id)->where('name', '=', 'Victoria')->first()){
                \App\Models\Location\Parish::create([
                    'country_id' => $trinidad->id,
                    'name' => 'Victoria',
                    'enabled' => 1,
                ]);
            }
        }




    }
}
