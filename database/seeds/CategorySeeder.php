<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {





        if(!\App\Models\Merchant\Category::where('name', '=', 'WiGrocery')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiGrocery',
                'friendly_url' => 'grocery',
                'enabled' => 1,
                'order' => 14,
            ]);
            $category->icon = 'assets/category/wigrocery.png';
            $category->cover_image = 'assets/covers/wigrocery_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiBeauty')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiBeauty',
                'friendly_url' => 'beauty',
                'enabled' => 1,
                'order' => 13,
            ]);
            $category->icon = 'assets/category/wibeauty.png';
            $category->cover_image = 'assets/covers/wibeauty_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiDrinks')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiDrinks',
                'friendly_url' => 'drinks',
                'enabled' => 1,
                'order' => 12,
            ]);
            $category->icon = 'assets/category/widrinks.png';
            $category->cover_image = 'assets/covers/widrinks_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiEats')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiEats',
                'friendly_url' => 'eats',
                'enabled' => 1,
                'order' => 11,
            ]);
            $category->icon = 'assets/category/wieats.png';
            $category->cover_image = 'assets/covers/wieats_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiExercise')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiExercise',
                'friendly_url' => 'exercise',
                'enabled' => 1,
                'order' => 10,
            ]);
            $category->icon = 'assets/category/wiexercise.png';
            $category->cover_image = 'assets/covers/wiexercise_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiFarm')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiFarm',
                'friendly_url' => 'farm',
                'enabled' => 1,
                'order' => 9,
            ]);
            $category->icon = 'assets/category/wifarm.png';
            $category->cover_image = 'assets/covers/wifarm_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiFashion')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiFashion',
                'friendly_url' => 'fashion',
                'enabled' => 1,
                'order' => 8,
            ]);
            $category->icon = 'assets/category/wifashion.png';
            $category->cover_image = 'assets/covers/wifashion_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiFish')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiFish',
                'friendly_url' => 'fish',
                'enabled' => 1,
                'order' => 7,
            ]);
            $category->icon = 'assets/category/wifish.png';
            $category->cover_image = 'assets/covers/wifish_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiHardware')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiHardware',
                'friendly_url' => 'hardware',
                'enabled' => 1,
                'order' => 6,
            ]);
            $category->icon = 'assets/category/wihardware.png';
            $category->cover_image = 'assets/covers/wihardware_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiHome')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiHome',
                'friendly_url' => 'home',
                'enabled' => 1,
                'order' => 5,
            ]);
            $category->icon = 'assets/category/wihome.png';
            $category->cover_image = 'assets/covers/wihome_cover.jpg';
            $category->save();
        }


        if(!\App\Models\Merchant\Category::where('name', '=', 'WiJewellery')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiJewellery',
                'friendly_url' => 'jewellery',
                'enabled' => 1,
                'order' => 4,
            ]);
            $category->icon = 'assets/category/wijewellery.png';
            $category->cover_image = 'assets/covers/wijewellery_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiKids')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiKids',
                'friendly_url' => 'kids',
                'enabled' => 1,
                'order' => 3,
            ]);
            $category->icon = 'assets/category/wikids.png';
            $category->cover_image = 'assets/covers/wikids_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiPharmacy')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiPharmacy',
                'friendly_url' => 'pharmacy',
                'enabled' => 1,
                'order' => 2,
            ]);
            $category->icon = 'assets/category/wipharmacy.png';
            $category->cover_image = 'assets/covers/wipharmacy_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiTech')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiTech',
                'friendly_url' => 'tech',
                'enabled' => 1,
                'order' => 1,
            ]);
            $category->icon = 'assets/category/witech.png';
            $category->cover_image = 'assets/covers/witech_cover.jpg';
            $category->save();
        }

        if(!\App\Models\Merchant\Category::where('name', '=', 'WiWholesale')->first()){
            $category = \App\Models\Merchant\Category::create([
                'name' => 'WiWholesale',
                'friendly_url' => 'wholesale',
                'enabled' => 1,
                'order' => 0,
            ]);
            $category->icon = 'assets/category/wiwholesale.png';
            $category->cover_image = 'assets/covers/wiwholesale_cover.jpg';
            $category->save();
        }

    }
}
