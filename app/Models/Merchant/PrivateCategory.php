<?php

namespace App\Models\Merchant;

use App\Models\User\MerchantInfo;
use Illuminate\Database\Eloquent\Model;

class PrivateCategory extends Model
{
    protected $fillable = ['private_category_id', 'name', 'merchant_info_id', 'enabled'];

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function parent(){
        return $this->belongsTo(PrivateCategory::class,'private_category_id', 'id');
    }

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function merchant(){
        return $this->belongsTo(MerchantInfo::class,'merchant_info_id', 'id');
    }

    /**
     * Merchants
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function products(){
        return $this->hasMany(Product::class, 'private_category_id', 'id');
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
}
