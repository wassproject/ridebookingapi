<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Ride;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request, $rideId)
    {
        $userId = auth('user-api')->id();

        $ride = Ride::where('id', $rideId)
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->first();

        if (!$ride) {
            return response()->json([
                'status' => false,
                'message' => 'Ride not found or not completed yet'
            ], 404);
        }

        $validated = $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'feedback_type' => 'required|in:Feedback,Complain',
            'reason' => 'nullable|in:Delay,Misbehavior,App Issue,Others',
            'comments' => 'nullable|string',
        ]);

        $feedback = Feedback::create([
            'ride_id' => $ride->id,
            'user_id' => $userId,
            'driver_id' => $ride->driver_id,
            'rating' => $validated['rating'] ?? null,
            'feedback_type' => $validated['feedback_type'] ?? null,
            'reason' => $validated['reason'] ?? null,
            'comments' => $validated['comments'] ?? null,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Feedback submitted successfully',
            'feedback' => $feedback
        ]);
    }
}
