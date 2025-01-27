<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Model;

class NotificationRecipiet extends Model
{
    protected $fillable = ['email', 'phone'];

    /**
     * Get Targets
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function target(){
        return $this->hasMany(NotificationRecipietTarget::class,'notification_recipiet_id', 'id');
    }
}
