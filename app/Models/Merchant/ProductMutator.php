<?php

namespace App\Models\Merchant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMutator extends Model
{

    use SoftDeletes;

    protected $fillable = ['product_id', 'product_mutator_group_id', 'name', 'external_udid', 'extra_price', 'max_quantity', 'enabled'];

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function product(){
        return $this->belongsTo(ProductMutator::class,'product_id', 'id');
    }

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function group(){
        return $this->belongsTo(ProductMutatorGroup::class,'product_mutator_group_id', 'id');
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

