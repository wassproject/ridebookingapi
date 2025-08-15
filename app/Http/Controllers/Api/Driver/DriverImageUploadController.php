<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverImageUploadController extends Controller
{
    public function uploadUserPhoto(Request $request)
    {
        return $this->uploadImage($request, 'user_photo');
    }

    public function uploadDlPhoto(Request $request)
    {
        return $this->uploadImage($request, 'dl_photo');
    }

    public function uploadAadhaarPhotos(Request $request)
    {
        $request->validate([
            'aadhaar_front_photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'aadhaar_back_photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $driver = Auth::guard('sanctum')->user();

        $frontPath = $request->file('aadhaar_front_photo')->store('aadhaar', 'public');
        $backPath = $request->file('aadhaar_back_photo')->store('aadhaar', 'public');

        $driver->update([
            'aadhaar_front_photo' => $frontPath,
            'aadhaar_back_photo' => $backPath,
        ]);

        return response()->json([
            'message' => 'Aadhaar photos uploaded',
            'aadhaar_front_photo' => $frontPath,
            'aadhaar_back_photo' => $backPath,
        ]);
    }

    public function uploadPanCardPhoto(Request $request)
    {
        return $this->uploadImage($request, 'pan_card_photo');
    }

    public function uploadSelfiePhoto(Request $request)
    {
        return $this->uploadImage($request, 'selfie_photo');
    }

    protected function uploadImage(Request $request, $fieldName)
    {
        $request->validate([
            $fieldName => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = $request->file($fieldName)->store($fieldName, 'public');

        $driver = Auth::guard('sanctum')->user();
        $driver->$fieldName = $path;
        $driver->save();

        return response()->json([
            'message' => ucfirst(str_replace('_', ' ', $fieldName)) . ' uploaded successfully',
            'path' => $path,
        ]);
    }
}
