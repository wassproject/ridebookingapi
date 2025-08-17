<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\AppFeedback;
use Illuminate\Http\Request;

class AppFeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'title'  => 'nullable|string|max:255',
            'review' => 'nullable|string',
        ]);

        $feedback = AppFeedback::create([
            'user_id' => auth('user-api')->id(), // optional if user is logged in
            'rating'  => $request->rating,
            'title'   => $request->title,
            'review'  => $request->review,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Thank you for your feedback!',
            'data'    => $feedback
        ]);
    }
}
