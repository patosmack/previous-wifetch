<?php

namespace App\Models\Merchant;

use App\Models\User\MerchantInfo;
use Illuminate\Database\Eloquent\Model;

class DeliveryTimeframe extends Model
{
    protected $fillable = ['merchant_info_id', 'name', 'order', 'enabled'];

    /**
     * Enabled Scope
     *
     * @param $query
     * @return mixed
     */

    public function scopeEnabled($query)
    {
        return $query->where('enabled', '=', 1);
    }

    /**
     * Sorted by Name Scope
     *
     * @param $query
     * @param string $direction
     * @return mixed
     */

    public function scopeSortedByOrder($query, $direction = 'ASC')
    {
        return $query->orderBy('order', $direction);
    }

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function merchant(){
        return $this->belongsTo(MerchantInfo::class,'merchant_info_id', 'id');
    }
}
