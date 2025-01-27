<?php

namespace App\Console\Commands\Old;

use App\Helpers\Helper;
use App\Helpers\MerchantHelper;
use App\Helpers\ProductHelper;
use App\Models\Location\Country;
use App\Models\Location\Parish;
use App\Models\Merchant\Category;
use App\Models\Merchant\PrivateCategory;
use App\Models\Merchant\Product;
use App\Models\User\MerchantInfo;
use App\Models\User\User;
use App\Models\User\UserAddress;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SyncOriginalDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:original {--mode=production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private $devMode;
    private $parishMap, $parishCountryMap, $countryMap, $categoryMap, $defaultPassword, $baseEmail;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->devMode = false;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        set_time_limit(0);


        if($this->option('mode') === 'production'){
            $this->devMode = false;
        }


        if($this->devMode){
            $this->defaultPassword = 'asd123*';
            $this->baseEmail = 'patosmack@gmail.com';
        }else{
            $this->defaultPassword = Str::random(20);
        }


        $filePath = storage_path('database/Database.json');

        $json = json_decode(file_get_contents($filePath), true)['__collections__'];

//        0 => "categories"
//  1 => "countries"
//  2 => "customers"
//  3 => "drivers"
//  4 => "merchants"
//  5 => "orders"

        $this->countryMap = Country::all()->pluck('id', 'name')->toArray();
        $this->parishMap = Parish::all()->pluck('id', 'name')->toArray();
        $this->parishCountryMap = Parish::all()->pluck('country_id', 'id')->toArray();
        $this->categoryMap = Category::all()->pluck('id', 'friendly_url')->toArray();

        $merchantFriendlyUrls = MerchantInfo::all()->pluck('id', 'friendly_url')->toArray();

//        dd($merchantFriendlyUrls);


        foreach ($json as $collection => $collectionData) {

            if($collection === 'customers'){
                self::migrateCustomers($collectionData);
            }
//            if($collection === 'categories'){
//                dd($collectionData);
//            }
            if ($collection === 'merchants') {
//

//
//                dateAdded
//"2020-05-31 13:22:48"
//dateUpdated
//"2020-06-02 15:36:49"
//id
//"0015WB2167oQ781TPhub"
//(string)
//image
//"https://firebasestorage.googleapis.com/v0/b/wifetch-app-prod.appspot.com/o/102968b9-3f8e-6feb-5bd2-f4c0eb08f804?alt=media&token=51b46d61-0946-4688-a2b4-cab8292b0f74"
//product_description
//"Crystal Farms Garden Vegetable Cream Cheese Spread 8 oz"
//product_photo
//file
//name
//"Crystal Farms Garden Vegetable Cream Cheese Spread 8 oz.png"
//type
//"image/png"
//product_price
//"5.99"
//product_title
//"Crystal Farms Garden Vegetable Cream Cheese Spread 8 oz"


//                0 => "companyLogo"
//  1 => "companyCountry"
//  2 => "category"
//  3 => "companyName"
//  4 => "dateAdded"
//  5 => "image"
//  6 => "status"
//  7 => "isActive"
//  8 => "companyEmail"
//  9 => "companyCategory"
//  10 => "contactName"
//  11 => "companyAddress"
//  "contactNumber"
//  "__collections__"
//  companyDescription
//                deliveryFee
//          serviceFee



                //pending, approved, cancelled
                foreach ($collectionData as $collection_merchant => $collectionData__merchant) {

//                    if($collection_merchant != 'De69eeNUWg6VDmn8xFjD'){
//                        continue;
//                    }


                    $country_id = self::idByKey($this->countryMap, self::getArrayValueByKey($collectionData__merchant, 'companyCountry', 'value'));
                    $category_id = self::idByKey($this->categoryMap, self::getArrayValueByKey($collectionData__merchant, 'category', 'label'));

                    $companyName = self::getArrayValueByKey($collectionData__merchant, 'companyName');
                    $companyEmail = self::getArrayValueByKey($collectionData__merchant, 'companyEmail');

                    if($this->baseEmail){
                        if($this->devMode){
                            list($baseEmailName, $baseEmailDomain)  = explode('@', $this->baseEmail);
                            $companyName = str_replace('-', '', str_replace('_', '', Helper::slug($companyName)));
                            $companyEmail = $baseEmailName . '+' . $companyName . '@' . $baseEmailDomain;
                        }
                    }


                    $companyCategory = self::idByKey($this->categoryMap, self::getArrayValueByKey($collectionData__merchant, 'companyCategory', 'label'));
                    $companyCategoryDop = self::getArrayValueByKey($collectionData__merchant, 'companyCategory', 'label');





                    $companyAddress = self::getArrayValueByKey($collectionData__merchant, 'companyAddress');
                    $companyDescription = self::getArrayValueByKey($collectionData__merchant, 'companyDescription');
                    $companyLogoUrl = self::getArrayValueByKey($collectionData__merchant, 'image');
                    $companyLogoName = self::getArrayValueByKey($collectionData__merchant, 'companyLogo', 'file', 'name');

                    $contactName = self::getArrayValueByKey($collectionData__merchant, 'contactName');
                    $contactNumber = self::getArrayValueByKey($collectionData__merchant, 'contactNumber');

                    $deliveryFee = (double)self::getArrayValueByKey($collectionData__merchant, 'deliveryFee');
                    $serviceFee = (double)self::getArrayValueByKey($collectionData__merchant, 'serviceFee');

                    $dateCreatedString = self::getArrayValueByKey($collectionData__merchant, 'dateAdded');
                    $dateUpdatedString = self::getArrayValueByKey($collectionData__merchant, 'dateUpdated');

                    $friendly_url = MerchantHelper::slug($companyName);

                    $status = self::getArrayValueByKey($collectionData__merchant, 'status');
                    $availableStatus = ['pending', 'approved', 'cancelled', 'rejected'];
                    if(!in_array($status, $availableStatus)){
                        $status = 'pending';
                    }

                    if($companyEmail){
                        $user = User::where('email', '=', $companyEmail)->first();
                        if($user){
                            $user->is_merchant = true;
                            $user->save();
                        }

                        if(!$user){
                            $user = new User();
                            $user->name = $contactName;
                            $user->email = $companyEmail;
                            $user->password = Hash::make($this->defaultPassword);
                            $user->home_phone = $contactNumber;
                            $user->external_udid = null;
                            if($user->save()){
                                $userAddress = new UserAddress();
                                $userAddress->name = 'Primary';
                                $userAddress->user_id = $user->id;
                                $userAddress->country_id = $country_id;
                                $userAddress->address = $companyAddress;
                                $userAddress->current = 1;
                                $userAddress->enabled = 1;
                                $userAddress->save();
                            }
                        }



                        $merchantInfo = MerchantInfo::where('external_udid', '=', $collection_merchant)->first();
                        if(!$merchantInfo){
                            $merchantInfo = new MerchantInfo();
                            $merchantInfo->user_id = $user->id;
                            $fileRelativePath = $this->downloadImage($companyLogoUrl, $companyLogoName, 'assets/uploads/merchant/');
                            $merchantInfo->logo = $fileRelativePath;

                            $merchantInfo->category_id = $category_id;
                            $merchantInfo->name = $companyName;
                            $merchantInfo->friendly_url = $friendly_url;
                            $merchantInfo->external_udid = $collection_merchant;

                            $merchantInfo->phone = $contactNumber;
                            $merchantInfo->email = $companyEmail;



                            $merchantInfo->description = $companyDescription;
                            $merchantInfo->contact_name = $contactName;
                            $merchantInfo->contact_phone = $contactNumber;
                            $merchantInfo->country_id = $country_id;
                            $merchantInfo->address = $companyAddress;
                            $merchantInfo->delivery_fee = $deliveryFee;
                            $merchantInfo->service_fee = $serviceFee;
                            if($status === 'approved'){
                                $merchantInfo->enabled = 1;
                            }
                            $merchantInfo->status = $status;
                            if($merchantInfo->save()){


                                $privateCategoryId = null;
                                if(!$companyCategory){
                                    if($companyCategoryDop){

                                        $privateCategoryName =  ucfirst(strtolower($companyCategoryDop));
                                        $privateCategory = PrivateCategory::where('merchant_info_id', '=', $merchantInfo->id)->where('name', '=', $privateCategoryName)->first();
                                        if(!$privateCategory){
                                            $privateCategory = new  PrivateCategory();
                                            $privateCategory->merchant_info_id = $merchantInfo->id;
                                            $privateCategory->name = $privateCategoryName;
                                            $privateCategory->enabled = 1;
                                            if($privateCategory->save()){
                                                $privateCategoryId = $privateCategory->id;
                                            }
                                        }
                                    }
                                }

                                $inventory = self::getArrayValueByKey($collectionData__merchant, '__collections__');
                                if(array_key_exists('inventory', $inventory)){
                                    foreach ($inventory['inventory'] as $inventoryKey => $inventoryData) {


                                        $productImageUrl = self::getArrayValueByKey($inventoryData, 'image');
                                        $productImageName = self::getArrayValueByKey($inventoryData, 'product_photo', 'file', 'name');
                                        $productPrice = (double)self::getArrayValueByKey($inventoryData, 'product_price');
                                        $productTitle = self::getArrayValueByKey($inventoryData, 'product_title');
                                        $productDescription = self::getArrayValueByKey($inventoryData, 'product_description');
                                        $itemDateCreatedString = self::getArrayValueByKey($inventoryData, 'dateAdded');
                                        $itemDateUpdatedString = self::getArrayValueByKey($inventoryData, 'dateUpdated');

                                        $fileRelativePath = $this->downloadImage($productImageUrl, $productImageName, 'assets/uploads/product/');

                                        $productSafeTitlte = $productTitle . '_' . Str::random(3) .  $merchantInfo->id;

                                        $friendly_url = ProductHelper::slug($productSafeTitlte);

                                        $product = new Product();
                                        $product->merchant_info_id = $merchantInfo->id;
                                        $product->friendly_url = $friendly_url;
                                        $product->name = $productTitle;
                                        $product->image = $fileRelativePath;
                                        $product->price = $productPrice;
                                        $product->always_on_stock = 1;
                                        $product->description = $productDescription;
                                        $product->searchable = $companyName . ' ' . $productTitle . ' ' . $productDescription . ' ';
                                        $product->enabled = 1;
                                        $product->external_udid = $inventoryKey;
                                        $product->private_category_id = $privateCategoryId;

                                        if($this->devMode){
                                            $discounts = [0, 10, 30, 50, 0, 0];
                                            $product->discount = $discounts[rand(0,count($discounts) -1)];
                                        }

                                        $product->save();
                                    }
                                }
                            }

                        }
                    }
                }
                $this->fillSomeData();
            }
        }


        return 0;
    }

    private function fillSomeData(){

        DB::statement( DB::raw("UPDATE user_addresses SET NAME = 'Primary'"));


        $product = Product::where('external_udid', '=', '3raSvBQZciD4Z1xrWWsk')->first();

        if($product){

            DB::statement( DB::raw("INSERT INTO `product_mutator_groups` (`product_id`, `name`, `choice_mode`, `allow_quantity_selector`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES (60910, 'Size', 'single', 0, 1, NULL, '2020-07-27 04:23:07', '2020-07-27 04:23:08');"));
            DB::statement( DB::raw("INSERT INTO `product_mutator_groups` (`product_id`, `name`, `choice_mode`, `allow_quantity_selector`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES (60910, 'Frost', 'multiple', 0, 1, NULL, '2020-07-27 04:23:51', '2020-07-27 04:23:52');"));
            DB::statement( DB::raw("INSERT INTO `product_mutator_groups` (`product_id`, `name`, `choice_mode`, `allow_quantity_selector`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES (60910, 'Candles', 'multiple', 1, 1, NULL, '2020-07-27 04:24:42', '2020-07-27 04:24:42');"));

            DB::statement( DB::raw("INSERT INTO `product_mutators` (`product_id`, `product_mutator_group_id`, `name`, `external_udid`, `extra_price`, `max_quantity`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES ({$product->id}, 1, 'Big', NULL, 10, 0, 1, NULL, '2020-07-27 04:26:00', '2020-07-27 04:26:01')"));
            DB::statement( DB::raw("INSERT INTO `product_mutators` (`product_id`, `product_mutator_group_id`, `name`, `external_udid`, `extra_price`, `max_quantity`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES ({$product->id}, 1, 'Extra Big', NULL, 15, 0, 1, NULL, '2020-07-27 04:26:24', '2020-07-27 04:26:24')"));
            DB::statement( DB::raw("INSERT INTO `product_mutators` (`product_id`, `product_mutator_group_id`, `name`, `external_udid`, `extra_price`, `max_quantity`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES ({$product->id}, 2, 'Blue', NULL, 2, 0, 1, NULL, '2020-07-27 04:26:52', '2020-07-27 04:26:53')"));
            DB::statement( DB::raw( "INSERT INTO `product_mutators` (`product_id`, `product_mutator_group_id`, `name`, `external_udid`, `extra_price`, `max_quantity`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES ({$product->id}, 2, 'Red', NULL, 2, 0, 1, NULL, '2020-07-27 04:27:15', '2020-07-27 04:27:16')"));
            DB::statement( DB::raw("INSERT INTO `product_mutators` (`product_id`, `product_mutator_group_id`, `name`, `external_udid`, `extra_price`, `max_quantity`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES ({$product->id}, 2, 'Green', NULL, 2, 0, 1, NULL, '2020-07-27 04:27:32', '2020-07-27 04:27:32')"));
            DB::statement( DB::raw("INSERT INTO `product_mutators` (`product_id`, `product_mutator_group_id`, `name`, `external_udid`, `extra_price`, `max_quantity`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES ({$product->id}, 2, 'Gold', NULL, 10, 0, 1, NULL, '2020-07-27 04:27:32', '2020-07-27 04:27:32')"));
            DB::statement( DB::raw("INSERT INTO `product_mutators` (`product_id`, `product_mutator_group_id`, `name`, `external_udid`, `extra_price`, `max_quantity`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES ({$product->id}, 3, 'With Stripes', NULL, 5, 0, 0, NULL, '2020-07-27 04:28:11', '2020-07-27 04:28:12')"));
            DB::statement( DB::raw("INSERT INTO `product_mutators` (`product_id`, `product_mutator_group_id`, `name`, `external_udid`, `extra_price`, `max_quantity`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES ({$product->id}, 3, 'With Balloons', NULL, 5, 0, 0, NULL, '2020-07-27 04:28:11', '2020-07-27 04:28:12')"));
            DB::statement( DB::raw("INSERT INTO `product_mutators` (`product_id`, `product_mutator_group_id`, `name`, `external_udid`, `extra_price`, `max_quantity`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES ({$product->id}, 3, 'With Hearts', NULL, 5, 0, 1, NULL, '2020-07-27 04:28:11', '2020-07-27 04:28:12')"));
            DB::statement( DB::raw("INSERT INTO `product_mutators` (`product_id`, `product_mutator_group_id`, `name`, `external_udid`, `extra_price`, `max_quantity`, `enabled`, `deleted_at`, `created_at`, `updated_at`) VALUES ({$product->id}, 3, 'With Stars', NULL, 5, 0, 1, NULL, '2020-07-27 04:28:11', '2020-07-27 04:28:12')"));
        }
    }

    private function migrateCustomers($collectionData){
        foreach ($collectionData as $collection_customer => $collectionData__customer) {
            $parish_id = self::idByKey($this->parishMap, self::getArrayValueByKey($collectionData__customer, 'user_parish'));
            $userEmail = self::getArrayValueByKey($collectionData__customer, 'user_email');
            $userName = self::getArrayValueByKey($collectionData__customer, 'user_name');
            $userMobileNumber = self::getArrayValueByKey($collectionData__customer, 'user_mobile_number');
            $userAddressStr = self::getArrayValueByKey($collectionData__customer, 'user_address');
            $userHomeNumber = self::getArrayValueByKey($collectionData__customer, 'user_home_number');





            if($userEmail && filter_var($userEmail, FILTER_VALIDATE_EMAIL)){
                if($this->baseEmail && filter_var($this->baseEmail, FILTER_VALIDATE_EMAIL)){
                    if($this->devMode){
                        list($baseEmailName, $baseEmailDomain)  = explode('@', $this->baseEmail);
                        $userName = str_replace('-', '', str_replace('_', '', Helper::slug($userName)));
                        $userEmail = $baseEmailName . '+' . $userName . '@' . $baseEmailDomain;
                    }
                }

                $user = User::where('email', '=', $userEmail)->first();
                if(!$user) {
                    $user = new User();
                    $user->email = $userEmail;
                    $user->password = Hash::make($this->defaultPassword);
                }
                $user->name = $userName ?: '';
                $user->home_phone = $userHomeNumber;
                $user->mobile_phone = $userMobileNumber;
                $user->name = $userName;
                $user->external_udid = $collection_customer;
                if($user->save()){
                    $userAddress = UserAddress::where('parish_id', '=', $parish_id)->where('address', '=', $userAddressStr)->first();
                    if(!$userAddress) {
                        $userAddress = new UserAddress();
                        $userAddress->user_id = $user->id;
                        $userAddress->parish_id = $parish_id;
                        $userAddress->address = $userAddressStr;
                    }
                    $country_id = 1;
                    if(array_key_exists($parish_id, $this->parishCountryMap)){
                        $country_id = $this->parishCountryMap[$parish_id];
                    }
                    $userAddress->country_id = $country_id;
                    $userAddress->current = 1;
                    $userAddress->enabled = 1;
                    $userAddress->save();
                }
            }
        }
    }

    private function generateMerchantFriendlyUrl($val)
    {

    }

    private function downloadImage($fileUrl, $fileName, $path ){
        if(!$fileUrl) return null;
        $extension = substr(strrchr($fileName, '.'), 1);
        if(!$extension) return  null;
        $fileNameBase = str_replace(".{$extension}", '', $fileName);
        $uploadBasepath =  rtrim($path, '/\\');

        $attachment_file_name = Helper::slug(md5($fileUrl) . '-' . $fileNameBase) . '.' .  strtolower($extension);

        $image_full_name = $uploadBasepath . '/'.$attachment_file_name;
        $public_image_full_name = public_path($image_full_name);
        if(File::exists($public_image_full_name)){
            return $image_full_name;
        }

        //Removing Image Download
        return null;

        $saved = false;
        try{
            $content = self::makeCurlCall($fileUrl);
            if($content) {
                file_put_contents($public_image_full_name, $content);
                $saved = true;
            }
        }catch (\Throwable $exception){
            $this->info('Error Saving Image: ' . $fileUrl);
            $this->info($exception->getMessage());
        }
        if(!$saved){
            try{
                $content = file_get_contents($fileUrl);
                $fp = fopen($public_image_full_name, "w");
                fwrite($fp, $content);
                fclose($fp);
                $saved = true;
            }catch (\Throwable $exception){
                $this->info('Error Saving Image Method 2: ' . $fileUrl);
                $this->info($exception->getMessage());
            }
        }
        if(!$saved){
            try{
                copy($fileUrl, $public_image_full_name);
                $saved = true;
            }catch (\Throwable $exception){
                $this->info('Error Saving Image Method 3: ' . $fileUrl);
                $this->info($exception->getMessage());
            }
        }
        if($saved){
            $this->resizeToCanvas($image_full_name);
            return $image_full_name;
        }

        return null;
    }

    private function resizeToCanvas($imagePath, $width = 1000, $height = 1000 ){
        try{
            $path = public_path($imagePath);
            $canvas = Image::canvas($width, $height, '#FFFFFF');
            $image = Image::make($path)->resize($width - ((20 * $width / 100)), $height - ((20 * $width / 100)),
                function ($constraint) {
                    $constraint->aspectRatio();
                });
            $canvas->insert($image, 'center');
            $canvas->save($path);
        }catch (\Throwable $exception){

        }
    }

    private function makeCurlCall($url)
    {
        $curl = curl_init();
        $timeout = 5;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    private function idByKey($context, $value)
    {
        if (array_key_exists($value, $context)) {
            return $context[$value];
        }
        return null;
    }

    private function getArrayValueByKey($arr, $key, $keyDeep1 = null, $keyDeep2 = null){
        if(is_array($arr) && array_key_exists($key, $arr)){
            $res = $arr[$key];
            if($keyDeep1) {
                if(is_array($res) && array_key_exists($keyDeep1, $res)) {
                    $resDeep1 = $res[$keyDeep1];
                    if ($keyDeep2) {
                        if (is_array($resDeep1) && array_key_exists($keyDeep2, $resDeep1)) {
                            return  self::fixText($resDeep1[$keyDeep2]);
                        }
                        return  null;
                    }
                    return  self::fixText($resDeep1);
                }
                return null;
            }
            return  self::fixText($res);
        }
        return null;
    }

    private function fixText($text){
        if(is_string($text)){
            $text = nl2br($text);
            $text = str_replace(['<br/>', '<br />'], '', preg_replace( "/<br>|\n/", " ", $text ));
        }
        return $text;
    }
}


