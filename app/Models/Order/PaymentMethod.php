<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'slug', 'order', 'name', 'description', 'success_description', 'cancelled_description', 'error_description', 'enabled'
    ];

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
