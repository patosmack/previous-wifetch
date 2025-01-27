<?php

namespace App\Models\Order;

use App\Models\Location\Country;
use App\Models\Location\Parish;
use App\Models\User\DriverInfo;
use App\Models\User\MerchantInfo;
use App\Models\User\User;
use App\Models\User\UserAddress;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeCoverage\Driver\Driver;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'user_address_id', 'discount_id',
        'merchant_id',
        'order_name', 'order_last_name', 'order_email', 'order_home_phone', 'order_mobile_phone', 'order_comment',
        'delivery_driver_id', 'delivery_country', 'delivery_country_id', 'delivery_parish_id', 'delivery_parish', 'delivery_address', 'delivery_secondary_address', 'delivery_phone', 'delivery_lat', 'delivery_lon', 'delivery_instructions', 'delivery_date', 'delivery_timeframe_id', 'delivery_timeframe', 'delivery_cost', 'tracking_code',

        'transaction_payment_method_id', 'transaction_approved_total', 'transaction_total', 'transaction_shipping',
        'transaction_handling_cost', 'transaction_accounting_reference', 'transaction_status', 'transaction_id', 'transaction_info', 'transaction_extra', 'transaction_url',

//        'correction_transaction_payment_method_id', 'correction_transaction_approved_total', 'correction_transaction_total', 'correction_transaction_shipping',
//        'correction_transaction_handling_cost', 'correction_transaction_accounting_reference', 'correction_transaction_status', 'correction_transaction_id', 'correction_transaction_info', 'correction_transaction_extra', 'correction_transaction_url',

        'status', 'custom_product_request'

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'transaction_info' => 'json',
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
     * Get Driver
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function driver(){
        return $this->belongsTo(DriverInfo::class,'delivery_driver_id', 'id');
    }

    /**
     * Get Payment Method
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class,'transaction_payment_method_id', 'id');
    }

    /**
     * Get Correction Payment Method
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function correctionPaymentMethod(){
        return $this->belongsTo(PaymentMethod::class,'correction_transaction_payment_method_id', 'id');
    }

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function merchant(){
        return $this->belongsTo(MerchantInfo::class,'merchant_id', 'id');
    }

    /**
     * Get Correction Payment Method
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function country(){
        return $this->belongsTo(Country::class,'delivery_country_id', 'id');
    }

    /**
     * Get Correction Payment Method
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function parish(){
        return $this->belongsTo(Parish::class,'delivery_parish_id', 'id');
    }

    /**
     * Get Address
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function address(){
        return $this->hasOne(UserAddress::class,'user_address_id', 'id');
    }

    /**
     * Get Discount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function discount(){
        return $this->hasOne(Discount::class,'id', 'discount_id');
    }

    /**
     * Get Cart Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function items(){
        return $this->hasMany(OrderItem::class,'order_id', 'id');
    }

    /**
     * Get TimeFrame
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function timeframe(){
        return $this->hasOne(Timeframe::class,'delivery_timeframe_id', 'id');
    }

    /**
     * Get Cart Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function logs(){
        return $this->hasMany(OrderStatusLog::class,'order_id', 'id');
    }

    /**
     * Get Extra Transactions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function transactions(){
        return $this->hasMany(OrderTransaction::class,'order_id', 'id');
    }
}
