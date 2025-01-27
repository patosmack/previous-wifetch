<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_payment_method_id', 'transaction_approved_total', 'transaction_total', 'transaction_shipping',
        'transaction_handling_cost', 'transaction_accounting_reference', 'transaction_status', 'transaction_id', 'transaction_info', 'transaction_extra', 'transaction_url', 'transaction_description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'transaction_info' => 'json',
    ];


    /**
     * Get Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function order(){
        return $this->belongsTo(Order::class,'order_id', 'id');
    }
}
