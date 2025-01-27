<?php

namespace App\Models\Merchant;

use App\Models\User\MerchantInfo;
use Illuminate\Database\Eloquent\Model;

class MerchantTag extends Model
{
    protected $fillable = ['tag_id', 'merchant_info_id'];

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

    public function tag(){
        return $this->belongsTo(Tag::class,'tag_id', 'id');
    }

}
