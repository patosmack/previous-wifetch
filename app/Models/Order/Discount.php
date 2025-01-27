<?php

namespace App\Models\Order;

use App\Models\User\MerchantInfo;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['code', 'rate', 'consumable', 'quantity', 'enabled', 'is_percentage'];

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function merchant(){
        return $this->belongsTo(MerchantInfo::class,'merchant_info_id', 'id');
    }
}
