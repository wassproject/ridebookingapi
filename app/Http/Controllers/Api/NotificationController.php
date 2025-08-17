<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('user-api')->user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                if ($item->created_at->isToday()) {
                    return 'Today';
                } elseif ($item->created_at->isYesterday()) {
                    return 'Yesterday';
                } else {
                    return $item->created_at->format('l'); // e.g. Sunday
                }
            });

        return response()->json([
            'status' => true,
            'data'   => $notifications,
        ]);
    }

    /**
     * 🔹 USER API: Mark as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * 🔹 ADMIN API: Send notification to one user
     */
    public function sendToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title'   => 'required|string',
            'message' => 'required|string',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'title'   => $request->title,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Notification sent to user',
            'data' => $notification
        ]);
    }

    /**
     * 🔹 ADMIN API: Send notification to all users
     */
    public function sendToAll(Request $request)
    {
        $request->validate([
            'title'   => 'required|string',
            'message' => 'required|string',
        ]);

        $users = User::all();
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = Notification::create([
                'user_id' => $user->id,
                'title'   => $request->title,
                'message' => $request->message,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Notification sent to all users',
            'count'   => count($notifications)
        ]);
    }
}
