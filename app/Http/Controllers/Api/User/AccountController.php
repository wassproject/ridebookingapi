<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function destroy(Request $request)
    {
        $request->validate([
            'delete_reason_id' => 'required|exists:delete_reasons,id',
        ]);

        $user = Auth::user();
        $user->delete_reason_id = $request->delete_reason_id;
        $user->save();

        $user->delete(); // Soft delete

        return response()->json([
            'status' => true,
            'message' => 'Account deleted successfully'
        ]);
    }
}
