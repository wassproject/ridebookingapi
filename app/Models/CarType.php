<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    protected $fillable = ['name', 'description'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
