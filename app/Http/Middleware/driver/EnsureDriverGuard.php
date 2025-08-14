<?php

namespace App\Http\Middleware\driver;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDriverGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('driver-api')->check()) {
            return response()->json(['message' => 'Unauthorized. Only drivers can access this.'], 403);
        }

        return $next($request);
    }
}
