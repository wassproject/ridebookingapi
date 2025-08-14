<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ This is correct
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Authenticatable // ✅ FIXED
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'middle_name', 'last_name', 'email', 'phone', 'dob', 'aadhaar_number', 'pan_number',
        'dl_number', 'dl_validity_date', 'dl_registration_date', 'dl_permit',
        'dl_photo', 'aadhaar_front_photo', 'aadhaar_back_photo', 'pan_card_photo', 'selfie_photo',
        'address', 'address_line_2', 'state', 'city', 'ward_or_area', 'pin_code',
        'user_photos', 'declaration_form','pin'
    ];

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }
    public function car()
{
    return $this->hasOne(Car::class);
}
    public function rechargeHistories()
    {
        return $this->hasMany(RechargeHistory::class);
    }
}
