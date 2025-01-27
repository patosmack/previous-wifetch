<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    protected $fillable = [
        'order_id', 'status', 'message'
    ];
}
