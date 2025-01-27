<?php

namespace App\Models\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'birthday', 'gender', 'home_phone', 'mobile_phone', 'email', 'password', 'enabled'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'external_platform', 'external_slug', 'external_udid', 'is_admin', 'is_merchant', 'is_driver'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get Addresses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function addresses(){
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }


    /**
     * Get Merchant Info
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function merchantInfo(){
        return $this->hasOne(MerchantInfo::class, 'user_id', 'id');
    }

    /**
     * Get Merchant Info
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function driverInfo(){
        return $this->hasOne(DriverInfo::class, 'user_id', 'id');
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
     * Available Scope
     *
     * @param $query
     * @return mixed
     */

    public function scopeAvailable($query){
        return $query->enabled();
    }

}
