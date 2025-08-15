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
         'car_type_id',
         'trip_starting_date',
         'trip_ending_date',
         'time',
         'hourly',
         'trip_type',
         'transmission'
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
    public function CarType()
    {
        return $this->belongsTo(CarType::class, 'car_type_id');
    }


}
