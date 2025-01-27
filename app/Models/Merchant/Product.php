<?php

namespace App\Models\Merchant;

use App\Models\User\MerchantInfo;
use App\Services\Calculations\ProductPrice;
use App\Services\Calculations\ProductStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    static $decimals = 2;

    protected $fillable = ['name', 'friendly_url', 'price', 'discount', 'stock', 'always_on_stock', 'max_quantity', 'description', 'unit', 'enabled', 'featured'];

    protected $appends = ['sellPrice', 'formattedSellPrice', 'originalPrice', 'formattedOriginalPrice', 'hasDiscount'];

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function merchant(){
        return $this->belongsTo(MerchantInfo::class,'merchant_info_id', 'id');
    }

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function privateCategory(){
        return $this->belongsTo(PrivateCategory::class,'private_category_id', 'id');
    }

    /**
     * Get Mutators
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function mutators(){
        return $this->hasMany(ProductMutator::class,'product_id', 'id');
    }

    /**
     * Get Mutators Groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function mutatorGroups(){
        return $this->hasMany(ProductMutatorGroup::class,'product_id', 'id');
    }

    /**
     * Enabled Scope
     *
     * @param $query
     * @return mixed
     */

    public function scopeEnabled($query){
        return $query->where('enabled', '=', 1);
    }

    /**
     * Featured Scope
     *
     * @param $query
     * @return mixed
     */

    public function scopeFeatured($query){
        return $query->where('featured', '=', 1);
    }

    /**
     * Available Scope
     *
     * @param $query
     * @return mixed
     */

    public function scopeAvailable($query){
        return $query->enabled();
    }

    /**
     * Get Available Stock Attribute
     * @return int
     */

    function getAvailableStockAttribute() {
        $itm = new ProductStock($this);
        return $itm->availableStock();
    }

    /**
     * Get Selling Price Attribute
     * @return double
     */

    function getSellPriceAttribute() {
        $itm = new ProductPrice($this);
        return $itm->sellPrice();
    }


    /**
     * Get Selling Price Attribute
     * @return double
     */

    function getFormattedSellPriceAttribute() {
        return number_format($this->getSellPriceAttribute(), static::$decimals);
    }


    /**
     * Get Original Price Attribute
     * @return double
     */

    function getOriginalPriceAttribute() {
        $itm = new ProductPrice($this);
        return $itm->originalPrice();
    }

    /**
     * Get Original Price Attribute
     * @return double
     */

    function getFormattedOriginalPriceAttribute() {
        return (float)number_format($this->getOriginalPriceAttribute(), static::$decimals);
    }

    /**
     * Get Original Price Attribute
     * @return double
     */

    function getHasDiscountAttribute() {
        return $this->getSellPriceAttribute() !== $this->getOriginalPriceAttribute();
    }



}
