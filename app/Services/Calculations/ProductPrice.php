<?php


namespace App\Services\Calculations;
use App\Models\Merchant\Product;
use App\Models\Merchant\ProductMutator;

class ProductPrice
{

    static $decimals = 2;
    protected $product;
    protected $quantity;
    private $mutators = [];
    private $discounts = [];

    public function __construct(Product $product, $quantity = 1){
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * Add Price to current Base Price
     */

    public function addMutator(ProductMutator $mutator, $quantity = 1){
        $mutator->order_quantity = $quantity;
        $this->mutators[] = $mutator;
        return $this;
    }

    /**
     * Add Price to current Base Price
     */

    public function addDiscount($discountValue){
        $this->discounts[] = $discountValue;
        return $this;
    }


    /**
     * Get Sell Price
     * @return float|int
     */

    public function sellPrice(){
        return $this->formatPrice($this->basePrice() - $this->amountToDiscount());
    }

    /**
     * Get Order & Cart Price
     * @return float|int
     */

    public function orderPrice(){
        $price = (($this->basePrice() - $this->amountToDiscount()) + $this->mutatorsPrice()) * $this->quantity;
        return $this->formatPrice($price);
    }

    /**
     * Get Original Price
     * @return float|int
     */

    public function originalPrice(){
        $price = $this->basePrice() * $this->quantity;
        return $this->formatPrice($price);
    }

    /**
     * Get Original Order & Cart Price
     * @return float|int
     */

    public function originalOrderPrice(){
        $price = $this->originalPrice() + $this->mutatorsPrice() * $this->quantity;
        return $this->formatPrice($price);
    }

    /**
     * Get Amount to Discount
     * @return float|int
     */

    public function amountToDiscount(){
        return $this->discountedPrice();
    }

    /**
     * Get Base Price
     * @return float|int
     */

    public function basePrice(){
        return $this->product->price;
    }

    /**
     * Get Base Price
     * @return float|int
     */

    private function mutatorsPrice(){
        $mutatorsPrice = 0;
        foreach ($this->mutators as $mutator){
            $mutatorsPrice += $mutator->extra_price * $mutator->order_quantity;
        }
        return $mutatorsPrice;
    }

    /**
     * Get Discounted Price
     * @return float|int
     */

    private function discountedPrice(){

        $basePrice = $this->basePrice() ;
        $discountedPrice = ($basePrice * ($this->product->discount / 100));
//        foreach ($this->discounts as $discount){
//            $discountedPrice += ($basePrice * ($discount / 100));
//        }
        return $discountedPrice;
    }


    /**
     * Format Price
     * @param $price
     * @return float
     */

    private function formatPrice($price){
        return (float)round($price, static::$decimals);
    }

}
