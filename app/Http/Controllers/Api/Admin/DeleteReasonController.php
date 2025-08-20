<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeleteReason;
use Illuminate\Http\Request;

class DeleteReasonController extends Controller
{
    public function index()
    {
        return response()->json(DeleteReason::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $reason = DeleteReason::create($request->only('description'));

        return response()->json([
            'status' => true,
            'message' => 'Reason created successfully',
            'data' => $reason
        ]);
    }

    public function destroy($id)
    {
        $reason = DeleteReason::findOrFail($id);
        $reason->delete();

        return response()->json([
            'status' => true,
            'message' => 'Reason deleted successfully'
        ]);
    }
}
