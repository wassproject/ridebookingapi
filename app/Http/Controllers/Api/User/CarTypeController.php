<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\CarType;
use Illuminate\Http\Request;

class CarTypeController extends Controller
{
    public function index()
    {
        // Fetch all car types from DB
        $carTypes = CarType::select( 'id','name')->get();

        // Return JSON response
        return response()->json([
            'status' => true,
            'message' => 'Car types fetched successfully',
            'data' => $carTypes
        ]);
    }
}
