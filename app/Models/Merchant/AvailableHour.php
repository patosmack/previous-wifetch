<?php

namespace App\Models\Merchant;

use App\Models\User\MerchantInfo;
use Illuminate\Database\Eloquent\Model;

class AvailableHour extends Model
{
    protected $fillable = ['merchant_info_id', 'day', 'open_time', 'close_time'];

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function merchant(){
        return $this->belongsTo(MerchantInfo::class,'merchant_info_id', 'id');
    }
}
