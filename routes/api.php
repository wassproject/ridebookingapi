<?php

use App\Http\Controllers\Api\Driver\DriverImageUploadController;
use App\Http\Controllers\Api\Driver\DriverPrivacyController;
use App\Http\Controllers\Api\Driver\DriverRideController;
use App\Http\Controllers\Api\Driver\DriverWalletController;
use App\Http\Controllers\Api\User\UserDashboardController;
use App\Http\Controllers\Api\User\UserRideController;
use App\Http\Controllers\Api\Driver\DriverCarController;
use App\Http\Controllers\Admin\AdminCarTypeController;
use App\Http\Controllers\Admin\AdminTimeRangeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

//user registration
Route::post('/register/user/request-otp', [AuthController::class, 'registerUserRequestOtp']);
Route::post('/register/user/verify-otp', [AuthController::class, 'verifyUserOtp']);
Route::post('/register/user', [AuthController::class, 'registerUser']);

//driver registration
Route::post('/register/driver/request-otp', [AuthController::class, 'registerDriverRequestOtp']);
Route::post('/register/driver/verify-otp', [AuthController::class, 'verifyDriverOtp']);
Route::post('/register/driver', [AuthController::class, 'registerDriver']);

// term and condition for driver
Route::get('/driver/terms', [AuthController::class, 'getTerms']);

// login for both driver and user
Route::post('/login/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/login/verify-otp', [AuthController::class, 'verifyOtp']);

//admin for saving the car details and time
Route::apiResource('admin/car-types', AdminCarTypeController::class);
Route::apiResource('admin/time-ranges', AdminTimeRangeController::class);

// Authenticated routes user
Route::middleware(['auth:sanctum', 'only_user'])->group(function () {
    Route::get('/user/getbanners', [UserDashboardController::class, 'getBanners']);
    Route::post('/user/postbanners', [UserDashboardController::class, 'storeBanner']);
    Route::put('/user/updatebanner/{id}', [UserDashboardController::class, 'updateBanner']);

    Route::post('/user/rides', [UserRideController::class, 'store']);          // Book ride
    Route::get('/user/rides', [UserRideController::class, 'index']);           // Ride history
    Route::get('/user/rides/{id}', [UserRideController::class, 'show']);       // Specific ride details
});

// Authenticated routes driver
Route::middleware(['auth:sanctum', 'only_driver'])->group(function () {
    // Separate APIs for image upload
    Route::post('/driver/upload/user-photo', [DriverImageUploadController::class, 'uploadUserPhoto']);
    Route::post('/driver/upload/dl-photo', [DriverImageUploadController::class, 'uploadDlPhoto']);
    Route::post('/driver/upload/aadhaar', [DriverImageUploadController::class, 'uploadAadhaarPhotos']);
    Route::post('/driver/upload/pan-card-photo', [DriverImageUploadController::class, 'uploadPanCardPhoto']);
    Route::post('/driver/upload/selfie-photo', [DriverImageUploadController::class, 'uploadSelfiePhoto']);
    Route::get('/driver/privacy-policy', [DriverPrivacyController::class, 'show']);

    Route::get('/driver/rides/upcoming', [DriverRideController::class, 'upcoming']);
    Route::get('/driver/rides/today', [DriverRideController::class, 'today']);
    Route::get('/driver/rides/past', [DriverRideController::class, 'past']);
    Route::get('/driver/rides/total', [DriverRideController::class, 'totalRides']);
    Route::get('/driver/rides/earnings', [DriverRideController::class, 'earnings']);
    Route::put('/driver/rides/{id}/accept', [DriverRideController::class, 'acceptRide']);
    Route::put('/driver/rides/{id}/status', [DriverRideController::class, 'updateStatus']);
    Route::post('/driver/update-location', [DriverRideController::class, 'updateLocation']);

    Route::get('/driver/rides/{id}', [DriverRideController::class, 'rideDetail']);
    //Route::get('/driver/profile', [DriverRideController::class, 'profile']);
    Route::match(['get', 'put'], '/driver/profile', [DriverRideController::class, 'profile']);
    Route::get('/driver/call-client/{ride_id}', [DriverRideController::class, 'callClient']);
    Route::get('/driver/car', [DriverCarController::class, 'getCarDetails']);
    Route::put('/driver/car', [DriverCarController::class, 'updateCarDetails']);
    Route::put('/driver/update-details', [DriverCarController::class, 'updateDetails']);
    // next day have to test these api
    Route::get('/driver/wallet', [DriverWalletController::class, 'balance']);
    Route::post('/driver/wallet/add', [DriverWalletController::class, 'addMoney']);
});


