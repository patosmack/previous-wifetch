<?php

namespace App\Models\Notification;

use App\Models\User\MerchantInfo;
use Illuminate\Database\Eloquent\Model;

class NotificationRecipietTarget extends Model
{

    protected $fillable = ['notification_recipiet_id', 'type', 'target'];

    /**
     * Get recipiet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function recipiet(){
        return $this->belongsTo(NotificationRecipiet::class,'notification_recipiet_id', 'id');
    }
}
