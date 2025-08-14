<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeRange extends Model
{
    protected $fillable = ['label', 'start_time', 'end_time'];

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'car_time_range');
    }
}
