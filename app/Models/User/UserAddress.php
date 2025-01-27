<?php

namespace App\Models\User;

use App\Models\Location\Country;
use App\Models\Location\Parish;
use App\Models\Merchant\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'country_id', 'parish_id', 'country_id', 'address', 'secondary_address', 'phone', 'lat',
        'lon', 'instructions'
    ];


    /**
     * Get User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    /**
     * Get Country
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function country(){
        return $this->belongsTo(Country::class,'country_id', 'id');
    }

    /**
     * Get Parish
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function parish(){
        return $this->belongsTo(Parish::class,'parish_id', 'id');
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

    /**
     * Current Scope
     *
     * @param $query
     * @return mixed
     */

    public function scopeCurrent($query){
        return $query->enabled()->where('current', '=', 1);
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
