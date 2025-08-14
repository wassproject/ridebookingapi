<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverPrivacyController extends Controller
{
    public function show()
    {
        $privacyText = <<<TEXT
Open Login is login system used by one of the pricing apps to onboard drivers.
Your data is securely stored and will not be shared without your consent.
By continuing, you agree to our terms & privacy policies.

Why do we need it?
To help onboard you faster and to ensure secure identity verification,
we ask you to upload your documents and photo. This is only used once and stored securely.
TEXT;

        return response()->json([

            'content' => $privacyText,
        ]);
    }
}
