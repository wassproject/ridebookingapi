<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::guard('user-api')->user();


        return response()->json([
            'status' => true,
            'user' => array_merge($user->toArray(), [
                'photo_url' => $user->photo ? asset('storage/' . $user->photo) : null,
            ]),
        ]);
    }

    // Update Profile
    public function update(Request $request)
    {
        $user = Auth::guard('user-api')->user();

        $validated = $request->validate([
            'name'  => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:15|unique:users,phone,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Fill other fields first (excluding photo)
        $user->fill(collect($validated)->except('photo')->toArray());

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('user_photos', 'public');
            $user->photo = $path; // ✅ only store the relative path
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => array_merge($user->toArray(), [
                'photo_url' => $user->photo ? asset('storage/' . $user->photo) : null,
            ]),
        ]);
    }

}
