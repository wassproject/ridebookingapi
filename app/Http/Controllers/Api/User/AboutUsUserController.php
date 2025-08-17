<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsUserController extends Controller
{
    public function show()
    {
        $about = AboutUs::first();

        return response()->json([
            'status' => true,
            'data' => $about ? $about->content : null
        ]);
    }
}
