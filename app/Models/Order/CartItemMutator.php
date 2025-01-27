<?php

namespace App\Models\Order;

use App\Models\Merchant\ProductMutator;
use Illuminate\Database\Eloquent\Model;

class CartItemMutator extends Model
{
    protected $fillable = [
        'cart_item_id', 'product_mutator_id', 'quantity'
    ];

    /**
     * Get Cart Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function item(){
        return $this->belongsTo(CartItem::class,'cart_item_id', 'id');
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
