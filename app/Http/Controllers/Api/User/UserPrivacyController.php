<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class UserPrivacyController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('user-api')->user(); // only authenticated users

        $terms = Term::where('user_type', 'user')->get();

        return response()->json([
            'terms' => $terms
        ]);
    }

}
