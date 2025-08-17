<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:15',
            'message' => 'required|string|max:1000',
        ]);

        $contact = ContactMessage::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Your message has been submitted successfully.',
            'data'    => $contact,
        ], 201);
    }
}
