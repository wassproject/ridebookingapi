<?php

namespace App\Http\Controllers;

use App\Helpers\OtpHelper;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function registerUserRequestOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => [
                'required',
                'regex:/^[0-9]{10}$/',
                'unique:users,phone', // Check in users table
                function ($attribute, $value, $fail) {
                    // Check in drivers table
                    if (\App\Models\Driver::where('phone', $value)->exists()) {
                        $fail('The phone number has already been taken.');
                    }
                }
            ],
        ], [
            'phone.regex' => 'The phone number must be exactly 10 digits.'
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Send OTP
     OtpHelper::generateOtp($request->phone, 'user');

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function verifyUserOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^[0-9]{10}$/'],
            'code'  => 'required'
        ], [
            'phone.regex' => 'The phone number must be exactly 10 digits.'
        ]);
        if (!OtpHelper::verifyOtp($request->phone, $request->code, 'user')) {
            return response()->json(['message' => 'Invalid or expired OTP'], 401);
        }

        // Mark OTP verified (session, cache or database)
        cache()->put('otp_verified_user_'.$request->phone, true, 600);

        return response()->json(['message' => 'OTP verified']);
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => [
                'required',
                'regex:/^[0-9]{10}$/',
                'unique:users'
            ],
            'gender' => 'required|in:male,female',
            'photo' => 'nullable|image',
            'address' => 'nullable|string',
        ], [
            'phone.regex' => 'The phone number must be exactly 10 digits.'
        ]);

        // Check OTP verified flag
        if (!cache()->pull('otp_verified_user_'.$request->phone)) {
            return response()->json(['message' => 'OTP not verified or expired'], 403);
        }

        $photoPath = null;

        if ($request->hasFile('photo')) {
            // Store the uploaded file in storage/app/public/user_photos
            $photoPath = $request->file('photo')->store('user_photos', 'public');
        }

        $user = User::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'gender' =>  $request->gender,
            'photo'   => $photoPath, // save path instead of raw file
            'address' => $request->address,
        ]);



        return response()->json(['message' => 'User registered successfully', 'user' => $user, ]);
    }

    public function registerDriverRequestOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => [
                'required',
                'regex:/^[0-9]{10}$/',
                'unique:drivers,phone', // Check in drivers table
                function ($attribute, $value, $message) {
                    // Check in users table as well
                    if (\App\Models\User::where('phone', $value)->exists()) {
                        $message('The phone number has already been taken.');
                    }
                }
            ],
        ], [
            'phone.regex' => 'The phone number must be exactly 10 digits.'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Generate OTP for driver
        OtpHelper::generateOtp($request->phone, 'driver');

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function verifyDriverOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^[0-9]{10}$/'],
            'code'  => 'required'
        ], [
            'phone.regex' => 'The phone number must be exactly 10 digits.'
        ]);

        if (!OtpHelper::verifyOtp($request->phone, $request->code, 'driver')) {
            return response()->json(['message' => 'Invalid or expired OTP'], 401);
        }

        cache()->put('otp_verified_driver_'.$request->phone, true, 600);

        return response()->json(['message' => 'OTP verified']);
    }


    public function registerDriver(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|email|unique:drivers',
            'phone' => [
                'required',
                'regex:/^[0-9]{10}$/',
                'unique:drivers'
            ],
            'dob' => 'required|date',
            'aadhaar_number' => 'required|string',
            'pan_number' => 'required|string',
            'address' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'ward_or_area' => 'nullable|string',
            'pin_code' => 'nullable|string',
            'dl_number' => 'required|string',
            'dl_validity_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $expiryDate = \Carbon\Carbon::parse($value);
                    if ($expiryDate->lessThanOrEqualTo(now()->addMonths(6))) {
                        $fail('The driving licence must be valid for more than 6 months from today.');
                    }
                }
            ],
            'dl_registration_date' => 'required|date',
            'dl_permit' => 'required|string',
            'declaration_form' => 'required|boolean',
        ], [
            'phone.regex' => 'The phone number must be exactly 10 digits.',
        ]);

        if (!cache()->pull('otp_verified_driver_' . $request->phone)) {
            return response()->json(['message' => 'OTP not verified or expired'], 403);
        }

        $driver = Driver::create([
            'name' => $request->name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'aadhaar_number' => $request->aadhaar_number,
            'pan_number' => $request->pan_number,
            'address' => $request->address,
            'address_line_2' => $request->address_line_2,
            'state' => $request->state,
            'city' => $request->city,
            'ward_or_area' => $request->ward_or_area,
            'pin_code' => $request->pin_code,
            'dl_number' => $request->dl_number,
            'dl_validity_date' => $request->dl_validity_date,
            'dl_registration_date' => $request->dl_registration_date,
            'dl_permit' => $request->dl_permit,
            'declaration_form' => $request->declaration_form,
        ]);

        $token = $driver->createToken('driver-token')->plainTextToken;

        return response()->json([
            'message' => 'Driver registered successfully',
            'token' => $token,
            'driver' => $driver,
        ]);
    }

    public function getTerms()
    {
        $termsText = "By registering as a driver, you agree to abide by all safety regulations, provide accurate documents, and maintain professional conduct.";

        return response()->json([
            'terms' => $termsText
        ]);
    }

    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
        ]);

        // Try to find user first
        $user = User::where('phone', $request->phone)->first();
        $type = null;

        if ($user) {
            $type = 'user';
        } else {
            // If not a user, check driver
            $driver = Driver::where('phone', $request->phone)->first();
            if ($driver) {
                $type = 'driver';
            }
        }

        // If no record found in either table
        if (!$type) {
            return response()->json([
                'message' => 'Phone number not found in our records.'
            ], 404);
        }

        // Generate OTP using the detected type
        OtpHelper::generateOtp($request->phone, $type);

        return response()->json([
            'message' => 'OTP sent successfully',
             // user or driver
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'code'  => 'required',
        ]);

        // Detect type (user/driver) from phone
        $user = User::where('phone', $request->phone)->first();
        $type = null;

        if ($user) {
            $type = 'user';
        } else {
            $driver = Driver::where('phone', $request->phone)->first();
            if ($driver) {
                $type = 'driver';
            }
        }

        if (!$type) {
            return response()->json([
                'message' => 'Phone number not found in our records.'
            ], 404);
        }

        // Verify OTP
        if (!OtpHelper::verifyOtp($request->phone, $request->code, $type)) {
            return response()->json(['message' => 'Invalid or expired OTP'], 401);
        }

        // Fetch the authenticated user/driver
        $model = $type === 'user' ? User::class : Driver::class;
        $authUser = $model::where('phone', $request->phone)->first();

        if (!$authUser) {
            return response()->json(['message' => ucfirst($type) . ' not found'], 404);
        }

        // Generate token
        $token = $authUser->createToken('api-token')->plainTextToken;

        return response()->json([
            'token'  => $token,
            'status' => $type, // user or driver
            'user'   => $authUser
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('user-api')->user();

        if ($user) {
           // $user->currentAccessToken()->delete();
            $user->tokens()->delete();


            return response()->json([
                'message' => 'Logged out successfully'
            ]);
        }

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
