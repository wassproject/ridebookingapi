<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'driver_id',
        'type',          // optional (legacy)
        'car_type_id',   // link to admin car type
        'description',
        'transmission',
        // no available_at here; time ranges are in pivot
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function CarType()
    {
        return $this->belongsTo(CarType::class, 'car_type_id');
    }

    public function timeRanges()
    {
        return $this->belongsToMany(TimeRange::class, 'car_time_range');
    }
}
