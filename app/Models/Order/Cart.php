<?php

namespace App\Models\Order;

use App\Models\User\User;
use App\Models\User\UserAddress;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_token', 'user_id', 'user_address_id', 'discount_id', 'delivery_date', 'timeframe_id', 'comment', 'custom_product_request'
    ];

    protected $casts = [
        'custom_product_request' => 'array',
    ];

    /**
     * Get User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    /**
     * Get Address
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function address(){
        return $this->belongsTo(UserAddress::class,'user_address_id', 'id');
    }

//    /**
//     * Get Timeframe
//     *
//     * @return \Illuminate\Database\Eloquent\Relations\HasOne
//     */
//
//    public function timeframe(){
//        return $this->belongsTo(Timeframe::class,'timeframe_id', 'id');
//    }

    /**
     * Get Discount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function discount(){
        return $this->belongsTo(Discount::class,'discount_id', 'id');
    }

    /**
     * Get Cart Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function items(){
        return $this->hasMany(CartItem::class,'cart_id', 'id');
    }
}
