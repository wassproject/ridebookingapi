<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'amount',
        'status',
        'transaction_no',
        'payment_method',
    ];
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
