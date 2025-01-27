<?php

namespace App\Models\Order;

use App\Models\Merchant\ProductMutator;
use Illuminate\Database\Eloquent\Model;

class OrderItemMutator extends Model
{
    protected $fillable = [
        'order_item_id', 'product_mutator_id', 'quantity', 'name', 'extra_price'
    ];

    /**
     * Get Cart Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function item(){
        return $this->belongsTo(OrderItem::class,'order_item_id', 'id');
    }

    /**
     * Get Product Mutator
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function mutator(){
        return $this->belongsTo(ProductMutator::class,'product_mutator_id', 'id');
    }
}
