<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
     protected $fillable = [
        'user_id',
        'driver_id',
        'pickup_lat',
        'pickup_lng',
        'drop_lat',
        'drop_lng',
        'city_id',
        'pickup_location',
        'drop_location',
        'ride_time',
        'status',
        'fare',
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

}
