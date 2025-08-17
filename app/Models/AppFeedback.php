<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppFeedback extends Model
{
    protected $fillable = [
        'user_id',
        'rating',
        'title',
        'review',
    ];
    protected $table = 'app_feedback';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
