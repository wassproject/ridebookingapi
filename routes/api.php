<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Api\Admin\AboutUsAdminController;
use App\Http\Controllers\Api\Admin\AdminCarTypeController;
use App\Http\Controllers\Api\Admin\AdminTimeRangeController;
use App\Http\Controllers\Api\Admin\DeleteReasonController;
use App\Http\Controllers\Api\Admin\TermController;

use App\Http\Controllers\Api\Driver\DriverCarController;
use App\Http\Controllers\Api\Driver\DriverImageUploadController;
use App\Http\Controllers\Api\Driver\DriverPrivacyController;
use App\Http\Controllers\Api\Driver\DriverRideController;
use App\Http\Controllers\Api\Driver\DriverRideInviteController;
use App\Http\Controllers\Api\Driver\DriverWalletController;
use App\Http\Controllers\Api\NotificationController;

use App\Http\Controllers\Api\User\AboutUsUserController;
use App\Http\Controllers\Api\User\AccountController;
use App\Http\Controllers\Api\User\AppFeedbackController;
use App\Http\Controllers\Api\User\CarTypeController;
use App\Http\Controllers\Api\User\ContactController;
use App\Http\Controllers\Api\User\FeedbackController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\UserDashboardController;
use App\Http\Controllers\Api\User\UserPrivacyController;
use App\Http\Controllers\Api\User\UserRideController;
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


// login for both driver and user
Route::post('/login/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/login/verify-otp', [AuthController::class, 'verifyOtp']);

//admin login
Route::post('admin/login', [AdminAuthController::class, 'login']);



// term and condition for driver
Route::get('/driver/terms', [AuthController::class, 'getTerms']);


//admin for saving the car details and time


// Authenticated routes user
Route::middleware(['auth:sanctum', 'only_user'])->group(function () {
    Route::get('/user/getbanners', [UserDashboardController::class, 'getBanners']);
    Route::post('/user/postbanners', [UserDashboardController::class, 'storeBanner']);
    Route::put('/user/updatebanner/{id}', [UserDashboardController::class, 'updateBanner']);

    Route::post('/user/rides', [UserRideController::class, 'store']);
    Route::get('user/rides/{id}/show', [UserRideController::class, 'show']);  // show ride
    Route::post('user/rides/{id}/cancel', [UserRideController::class, 'cancelRide']); //cancel ride

    // routes/api.php
   //Route::get('user/rides/list', [UserRideController::class, 'listRides']);
//authenticated user id
    Route::get('/user/id', [UserRideController::class, 'getUserId']);
//user ride
    Route::get('user/rides/upcoming', [UserRideController::class, 'upcomingRides']);
    Route::get('user/rides/confirmed', [UserRideController::class, 'confirmedRides']);
    Route::get('user/rides/cancelled', [UserRideController::class, 'cancelledRides']);

    //user feedback
    Route::post('user/rides/{rideId}/feedback', [FeedbackController::class, 'store']);
   //user profile get and update
    Route::get('user/profile', [ProfileController::class, 'show']);
    Route::post('user/profile/update', [ProfileController::class, 'update']);

    //contact
    Route::post('user/contact', [ContactController::class, 'store']);

    //about us
    Route::get('user/about-us', [AboutUsUserController::class, 'show']);

    //appfeedback
    Route::post('user/app-feedback', [AppFeedbackController::class, 'store']);

    //Route::get('/user/rides', [UserRideController::class, 'index']);           // Ride history
    //Route::get('/user/rides/{id}', [UserRideController::class, 'show']);

    Route::get('user/car-types', [CarTypeController::class, 'index']);

    //notification
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    //delete profile

    Route::delete('user/account/delete', [AccountController::class, 'destroy']);

    //get t&c and privacy policy
    Route::get('user/terms', [UserPrivacyController::class, 'index']);

    Route::post('user/logout', [AuthController::class, 'logout']);


});






// Authenticated routes driver
Route::middleware(['auth:sanctum', 'only_driver'])->group(function () {
    // Separate APIs for image upload
    Route::post('/driver/upload/user-photo', [DriverImageUploadController::class, 'uploadUserPhoto']);
    Route::post('/driver/upload/dl-photo', [DriverImageUploadController::class, 'uploadDlPhoto']);
    Route::post('/driver/upload/aadhaar', [DriverImageUploadController::class, 'uploadAadhaarPhotos']);
    Route::post('/driver/upload/pan-card-photo', [DriverImageUploadController::class, 'uploadPanCardPhoto']);
    Route::post('/driver/upload/selfie-photo', [DriverImageUploadController::class, 'uploadSelfiePhoto']);
   // Route::get('/driver/privacy-policy', [DriverPrivacyController::class, 'show']);
    Route::get('/driver/privacy-policy', [DriverPrivacyController::class, 'index']);

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
 //   Route::put('/driver/update-details', [DriverCarController::class, 'updateDetails']);
    // next day have to test these api
    Route::get('/driver/wallet', [DriverWalletController::class, 'balance']);
    Route::post('/driver/wallet/add', [DriverWalletController::class, 'addMoney']);

    Route::get('/driver/rides/call/notify', [DriverRideInviteController::class, 'index']);
});


//authenticated route admin
Route::middleware('auth:admin-api')->group(function () {

    Route::apiResource('admin/car-types', AdminCarTypeController::class);
    Route::apiResource('admin/time-ranges', AdminTimeRangeController::class);

//notification to the user
    Route::post('/admin/notifications/user', [NotificationController::class, 'sendToUser']);
    Route::post('/admin/notifications/all', [NotificationController::class, 'sendToAll']);

    Route::post('admin/about-us/update', [AboutUsAdminController::class, 'update']);


    //create reason for user why they are deleting the profile
    Route::post('admin/delete-reasons', [DeleteReasonController::class, 'store']);
    Route::get('admin/delete-reasons', [DeleteReasonController::class, 'index']);
    Route::delete('admin/delete-reasons/{id}', [DeleteReasonController::class, 'destroy']);


    //privacy policy/term and condition
    Route::post('admin/terms', [TermController::class, 'storeOrUpdate']);
});
