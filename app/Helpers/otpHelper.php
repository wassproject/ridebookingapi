<?php

namespace App\Helpers;

use App\Models\Otp;
use Illuminate\Support\Facades\Log;

class OtpHelper
{
    // Generate a 6-digit OTP and store it in the database
    public static function generateOtp($phone, $userType)
    {
        // Use fixed OTP in local/dev, random in production
        $otp = config('app.env') === 'production' ? rand(100000, 999999) : 123456;

        Otp::updateOrCreate(
            ['phone' => $phone, 'user_type' => $userType],
            [
                'code' => $otp,
                'expires_at' => now()->addMinutes(5),
            ]
        );

        // Optional: remove log if you don't want to see it
        Log::info("OTP stored for $phone: $otp");

        return $otp;
    }

    // Verify if the OTP is valid (matches and not expired)
    public static function verifyOtp($phone, $code, $userType)
    {
        $otpRecord = Otp::where('phone', $phone)
            ->where('user_type', $userType)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->first();

        return $otpRecord ? true : false;
    }
}
