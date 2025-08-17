<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsAdminController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $about = AboutUs::first();
        if (!$about) {
            $about = AboutUs::create(['content' => $request->content]);
        } else {
            $about->update(['content' => $request->content]);
        }

        return response()->json([
            'status' => true,
            'message' => 'About Us updated successfully',
            'data' => $about
        ]);
    }
}
