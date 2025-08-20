<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:privacy_policy,terms_and_conditions',
            'user_type' => 'required|in:user,driver',
            'content' => 'required|string',
        ]);

        $term = Term::updateOrCreate(
            ['type' => $request->type, 'user_type' => $request->user_type],
            ['content' => $request->content]
        );

        return response()->json([
            'message' => 'Term saved successfully',
            'term' => $term
        ]);
    }
}
