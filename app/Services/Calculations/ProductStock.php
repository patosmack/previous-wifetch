<?php


namespace App\Services\Calculations;
use App\Models\Merchant\Product;
use App\Models\Merchant\ProductMutator;

class ProductStock
{
    protected $product;
    private $mutators = [];

    public function __construct(Product $product){
        $this->product = $product;
    }

    /**
     * Add Price to current Base Price
     */

    public function addMutator(ProductMutator $mutator){
        $this->mutators[] = $mutator;
        return $this;
    }


    /**
     * Get Amount to Discount
     * @return float|int
     */

    public function availableStock(){
        if($this->product->always_on_stock){
            return PHP_INT_MAX;
        }
        return (int)$this->product->stock;
    }


}
