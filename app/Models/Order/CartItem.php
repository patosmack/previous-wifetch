<?php

namespace App\Models\Order;

use App\Models\Merchant\Product;
use App\Models\Merchant\ProductMutator;
use App\Models\Merchant\ProductMutatorGroup;
use App\Models\User\MerchantInfo;
use App\Models\User\User;
use App\Models\User\UserAddress;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id', 'product_id', 'quantity'
    ];

    /**
     * Get Cart
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function cart(){
        return $this->belongsTo(Cart::class,'cart_id', 'id');
    }

    /**
     * Get Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function product(){
        return $this->belongsTo(Product::class,'product_id', 'id');
    }


    /**
     * Get Cart Item Mutators
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function mutators(){
        return $this->hasMany(CartItemMutator::class,'cart_item_id', 'id');
    }

}
