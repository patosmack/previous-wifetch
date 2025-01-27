<?php

namespace App\Models\Merchant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMutatorGroup extends Model
{

    use SoftDeletes;

    protected $fillable = ['product_id', 'name', 'choice_mode', 'allow_quantity_selector', 'enabled'];

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function product(){
        return $this->belongsTo(Product::class,'product_id', 'id');
    }

    /**
     * Get Mutators
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function mutators(){
        return $this->hasMany(ProductMutator::class,'product_mutator_group_id', 'id');
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
