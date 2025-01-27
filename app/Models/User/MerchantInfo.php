<?php

namespace App\Models\User;

use App\Helpers\CountryHelper;
use App\Models\Location\Country;
use App\Models\Location\Parish;
use App\Models\Merchant\AvailableHour;
use App\Models\Merchant\Category;
use App\Models\Merchant\DeliveryTimeframe;
use App\Models\Merchant\PrivateCategory;
use App\Models\Merchant\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MerchantInfo extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id', 'name', 'friendly_url', 'description', 'contact_name', 'contact_phone', 'contact_email',
        'country_id', 'parish_id', 'address', 'phone', 'email', 'lat', 'lon', 'notification_email', 'notification_phone', 'delivery_fee', 'service_fee','allow_custom_items',
        'disclaimer'
    ];

    /**
     * Get User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    /**
     * Get Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function category(){
        return $this->belongsTo(Category::class,'category_id', 'id');
    }

    /**
     * Get Products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function privateCategories(){
        return $this->hasMany(PrivateCategory::class, 'merchant_info_id', 'id');
    }

    /**
     * Get Country
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function country(){
        return $this->belongsTo(Country::class,'country_id', 'id');
    }

    /**
     * Get Parish
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function parish(){
        return $this->belongsTo(Parish::class,'parish_id', 'id');
    }

    /**
     * Get Products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function products(){
        return $this->hasMany(Product::class, 'merchant_info_id', 'id');
    }

    /**
     * Get Available Hours
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function availableHours(){
        return $this->hasMany(AvailableHour::class, 'merchant_info_id', 'id');
    }

    /**
     * Get Delivery Timeframes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function deliveryTimeframes(){
        return $this->hasMany(DeliveryTimeframe::class, 'merchant_info_id', 'id');
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
     * Feature Scope
     * Feature Scope
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
//        return $query->withCount('products')->enabled()->where('status', '=', 'approved')->having('products_count', '>', 0);
        return $query->withCount('products')->enabled()->where('status', '=', 'approved');
    }

    /**
     * Available Scope
     *
     * @param $query
     * @param Country $country
     * @return mixed
     */

    public function scopeFromCountry($query, $country = null){
        if(!$country){
            $country = CountryHelper::getCurrentCountry();
        }
        if($country){
            $query->where('country_id', '=', $country->id);
        }
        return $query;
    }

}
