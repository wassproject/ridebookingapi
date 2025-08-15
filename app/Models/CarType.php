<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    protected $fillable = ['name', 'description'];

    public function Car()
    {
        return $this->hasMany(Car::class);
    }
    public function rides()
    {
        return $this->hasMany(Ride::class, 'car_type_id');
    }
}
