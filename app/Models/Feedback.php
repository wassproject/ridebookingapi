<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable=['ride_id','user_id','driver_id',
        'rating','feedback_type','reason','comment'];
}
