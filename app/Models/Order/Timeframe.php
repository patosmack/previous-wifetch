<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Timeframe extends Model
{
    protected $fillable = ['name', 'order', 'enabled'];


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
     * Sorted by Name Scope
     *
     * @param $query
     * @param string $direction
     * @return mixed
     */

    public function scopeSortedByOrder($query, $direction = 'ASC'){
        return $query->orderBy('order', $direction);
    }
}
