<?php

namespace App\Models\Importer;

use App\Models\User\MerchantInfo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class ImportHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'user_id', 'merchant_info_id', 'description', 'file_name', 'udid', 'status', 'status_message', 'notification_sent'
    ];

    /**
     * The attributes that should be appended to the model.
     *
     * @var array
     */

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 'merchant_info_id','status', 'notification_sent','updated_at'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function merchant(){
        return $this->belongsTo(MerchantInfo::class);
    }
}
