<?php

namespace App\Models\Merchant;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['category_id', 'name', 'enabled'];

    /**
     * Get Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function category(){
        return $this->belongsTo(Category::class,'category_id', 'id');
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

}
