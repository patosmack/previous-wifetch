<?php

namespace App\Models\Merchant;

use App\Models\User\MerchantInfo;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'friendly_url', 'order', 'enabled'];

    /**
     * Get Merchants
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function merchants(){
        return $this->hasMany(MerchantInfo::class, 'category_id', 'id');
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
     * Sorted by Name Scope
     *
     * @param $query
     * @param string $direction
     * @return mixed
     */

    public function scopeSortedByOrder($query, $direction = 'DESC'){
        return $query->orderBy('order', $direction);
    }
}
