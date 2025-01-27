<?php

namespace App\Models\Location;

use App\Models\User\DriverInfo;
use App\Models\User\MerchantInfo;
use Illuminate\Database\Eloquent\Model;

class Parish extends Model
{

    protected $fillable = ['country_id', 'name', 'enabled'];

    /**
     * Get Country
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * Merchants
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function merchants(){
        return $this->hasMany(MerchantInfo::class, 'parish_id', 'id');
    }

    /**
     * Drivers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function drivers(){
        return $this->hasMany(DriverInfo::class, 'parish_id', 'id');
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
