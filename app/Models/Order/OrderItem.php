<?php

namespace App\Models\Order;

use App\Models\Merchant\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'name', 'price', 'quantity', 'unit'
    ];

    /**
     * Get Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * Get Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }


    /**
     * Get Cart Item Mutators
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function mutators()
    {
        return $this->hasMany(OrderItemMutator::class, 'order_item_id', 'id');
    }
}
