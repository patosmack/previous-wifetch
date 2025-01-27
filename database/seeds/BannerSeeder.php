<?php

use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createBanner('Grocery', route('merchants.by_category', 'grocery'), 'assets/banner/BannerHomeGrocery.jpg', 'assets/banner/BannerHomeGroceryMobile.jpg', 4);
        $this->createBanner('Beauty', route('merchants.by_category', 'beauty'), 'assets/banner/BannerHomeBeauty.jpg', 'assets/banner/BannerHomeBeautyMobile.jpg', 3);
        $this->createBanner('Drinks', route('merchants.by_category', 'grocery'), 'assets/banner/BannerHomeDrinks.jpg', 'assets/banner/BannerHomeDrinksMobile.jpg', 2);
        $this->createBanner('Eats', route('merchants.by_category', 'grocery'), 'assets/banner/BannerHomeFood.jpg', 'assets/banner/BannerHomeFoodMobile.jpg', 1);
    }

    private function createBanner($name, $target, $image, $imageMobile, $order){
        $banner = \App\Models\System\Banner::where('name', '=', $name)->first();
        if(!$banner) {
            $banner = new \App\Models\System\Banner();
        }
        $banner->name = $name;
        $banner->target = $target;
        $banner->image = $image;
        $banner->image_mobile = $imageMobile;
        $banner->order = $order;
        $banner->enabled = 1;
        $banner->save();
        return $banner;
    }
}
