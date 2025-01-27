<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    protected $fillable = ['name', 'iso', 'enabled'];

    /**
     * Parishes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function parishes(){
        return $this->hasMany(Parish::class, 'country_id', 'id');
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
     * Sorted by Name Scope
     *
     * @param $query
     * @param string $direction
     * @return mixed
     */

    public function scopeSortedByName($query, $direction = 'ASC'){
        return $query->orderBy('name', $direction);
    }
}
