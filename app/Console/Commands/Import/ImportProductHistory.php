<?php

namespace App\Console\Commands\Import;

use App\Helpers\Helper;
use App\Models\Importer\ImportHistory;
use App\Models\Merchant\PrivateCategory;
use App\Models\Merchant\Product;
use App\Models\User\MerchantInfo;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportProductHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileInProcess = ImportHistory::whereStatus('processing')->first();
        if($fileInProcess){
            $now = Carbon::now()->subMinutes(150);
            if($fileInProcess->created_at < $now){
                $fileInProcess->status = 'failed';
                $fileInProcess->status_message = 'The process could not be completed';
                $fileInProcess->save();
                return;
            }
            $this->info('Already Processing File');
            return;
        }

        $history = ImportHistory::whereStatus('pending')->first();
        if(!$history){
            $this->info('Nothing to Process');
            return;
        }
        $filePath = storage_path($history->file_name);
        if(!File::exists($filePath)){
            $history->status = 'failed';
            $history->status_message = 'The file could not be processed';
            $history->save();
        }else{
            $history->status = 'processing';
            $history->status_message = 'Importing file';
            $history->save();
            $this->processFile($filePath, $history->user_id, $history->merchant_info_id, $history);
        }
    }

    private function processFile($filePath, $user_id, $merchant_id, $history){

        $private_category_refs = PrivateCategory::select(['id', 'name'])->where('merchant_info_id', '=', $merchant_id)->get()->pluck('id', 'name')->toArray();
        $reader = ReaderEntityFactory::createReaderFromFile($filePath);
        $reader->setShouldPreserveEmptyRows(true);
        $reader->open($filePath);

        $headers = [];

        foreach ($reader->getSheetIterator() as $sheet) {

            $merchant_name = '';
            $merchant = MerchantInfo::find($merchant_id);
            if($merchant){
                $merchant_name = $merchant->name;
            }


            if ($sheet->getIndex() === 0) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    $row_data = $row->toArray();
                    if(count($row_data) >= 1){
                        if($rowNumber > 1) {

                            $name = trim($row_data[0]);
                            $category_name = trim($row_data[1]);
                            $price = trim($row_data[2]);
                            $discount = trim($row_data[3]);
                            $description = trim($row_data[4]);
                            $image_url = trim($row_data[5]);

                            $private_category_id = null;
                            if($category_name != ''){
                                if (isset($private_category_refs[$category_name])) {
                                    $private_category_id = $private_category_refs[$category_name];
                                }else{
                                    $privateCategory = new PrivateCategory();
                                    $privateCategory->merchant_info_id = $merchant_id;
                                    $privateCategory->name = $category_name;
                                    $privateCategory->enabled = 1;
                                    if($privateCategory->save()){
                                        $private_category_id = $privateCategory->id;
                                    }
                                }
                            }
                            $friendly_url = Helper::slug($name);
                            $product = Product::where('friendly_url', '=', $friendly_url)->first();
                            if($product){
                                if((int)$product->merchant_info_id !== (int)$merchant_id){
                                    $product = null;
                                    $friendly_url .= Helper::generateRandomNumber(5);
                                }
                            }
                            if(!$product){
                                $product = new Product();
                                $product->friendly_url = $friendly_url;
                                $product->merchant_info_id = $merchant_id;
                            }
                            $product->name = $name;
                            $product->private_category_id = $private_category_id;
                            $product->enabled = 1;
                            $product->price = $price;
                            $product->discount = $discount;
                            $product->always_on_stock = 1;
                            $product->description = $description;

                            $searchable = trim($merchant_name . ' ' . $name . ' ' . $description . ' ' . $category_name);
                            $product->searchable = $searchable;

                            if($image_url){
                                $path_info = pathinfo($image_url);
                                if($path_info){
                                    $extension = $path_info['extension'];
                                    $uploadBasepath =  rtrim('assets/uploads/product', '/\\');
                                    $attachment_file_name = Helper::stripString(Str::random(55)) . '.' .  $extension;
                                    $image_full_name = $uploadBasepath . '/'.$attachment_file_name;
                                    $public_image_full_name = public_path($image_full_name);
                                    try{
                                        $content = self::makeCurlCall($image_url);
                                        if($content) {
                                            file_put_contents($public_image_full_name, $content);
                                            $saved = true;
                                        }
                                    }catch (\Throwable $exception){
                                        $this->info('Error Saving Image: ' . $image_url);
                                        $this->info($exception->getMessage());
                                    }
                                    if(!$saved){
                                        try{
                                            $content = file_get_contents($image_url);
                                            $fp = fopen($public_image_full_name, "w");
                                            fwrite($fp, $content);
                                            fclose($fp);
                                            $saved = true;
                                        }catch (\Throwable $exception){
                                            $this->info('Error Saving Image Method 2: ' . $image_url);
                                            $this->info($exception->getMessage());
                                        }
                                    }
                                    if(!$saved){
                                        try{
                                            copy($image_url, $public_image_full_name);
                                            $saved = true;
                                        }catch (\Throwable $exception){
                                            $this->info('Error Saving Image Method 3: ' . $image_url);
                                            $this->info($exception->getMessage());
                                        }
                                    }
                                    if($saved){
                                        $product->image = $image_full_name;
                                    }
                                }
                            }
                            $product->save();
                        }
                    }
                }
            }
        }
        $reader->close();

        $history->status = 'processed';
        $history->status_message = 'Processed Successfully';
        $history->save();

    }

    private function makeCurlCall($url){
        $curl = curl_init();
        $timeout = 5;
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,$timeout);
        curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
