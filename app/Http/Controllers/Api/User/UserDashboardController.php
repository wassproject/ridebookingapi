<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function getBanners()
    {
        $banners = Banner::latest()->get()->map(function ($banner) {
            return [
                'id' => $banner->id,
                'image_url' => asset('storage/' . $banner->image),
            ];
        });

        return response()->json([
            'banners' => $banners
        ]);
    }
    public function storeBanner(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Store the uploaded image in storage/app/public/banners
        $path = $request->file('image')->store('user_photos/userbanners', 'public');

        // Save the path in the database
        $banner = new Banner();
        $banner->image = $path;
        $banner->save();

        return response()->json([
            'message' => 'Banner uploaded successfully',
            'banner' => [
                'id' => $banner->id,
                'image_url' => asset('storage/' . $banner->image),
            ]
        ]);
    }
    public function updateBanner(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $banner = Banner::findOrFail($id);

        // Delete old image if needed (optional)
        if ($banner->image && \Storage::disk('public')->exists($banner->image)) {
            \Storage::disk('public')->delete($banner->image);
        }

        $path = $request->file('image')->store('user_photos/userbanners', 'public');
        $banner->image = $path;
        $banner->save();

        return response()->json([
            'message' => 'Banner updated successfully',
            'banner' => [
                'id' => $banner->id,
                'image_url' => asset('storage/' . $banner->image),
            ]
        ]);
    }
}
