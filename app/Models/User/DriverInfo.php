<?php

namespace App\Models\User;

use App\Models\Location\Country;
use App\Models\Location\Parish;
use App\Models\Merchant\AvailableHour;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverInfo extends Model
{

    use SoftDeletes;

//$table->id();
//$table->unsignedInteger('user_id');
//$table->string('avatar')->nullable();
//$table->string('license')->nullable();
//$table->unsignedInteger('parish_id')->nullable();
//$table->string('address')->nullable();
//$table->string('phone')->nullable();
//$table->string('email')->nullable();
//
//$table->string('vehicle_plate')->nullable();
//$table->string('vehicle_brand')->nullable();
//$table->string('vehicle_model')->nullable();
//$table->string('vehicle_year')->nullable();
//$table->string('vehicle_color')->nullable();
//
//$table->string('license_image_front')->nullable();
//$table->string('license_image_back')->nullable();
//
//$table->boolean('enabled')->default(0);
//$table->enum('type', ['driver', 'fetcher', 'both', 'rejected'])->default('both');
//$table->enum('status', ['pending', 'approved', 'cancelled', 'rejected'])->default('pending');
//$table->timestamp('status_updated')->useCurrent();
//$table->timestamps();
//$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
//$table->foreign('parish_id')->references('id')->on('parishes')->onDelete('set null');

    protected $fillable = [
        'user_id', 'category_id','license', 'type',
        'country_id', 'parish_id', 'address', 'phone', 'email',
        'vehicle_plate', 'vehicle_brand', 'vehicle_model', 'vehicle_year', 'vehicle_color'
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
     * Get Available Hours
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function availableHours(){
        return $this->hasMany(AvailableHour::class, 'driver_info_id', 'id');
    }

}
