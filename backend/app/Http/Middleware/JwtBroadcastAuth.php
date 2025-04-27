<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class JwtBroadcastAuth
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            Auth::shouldUse('api');
            Auth::setUser($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        return $next($request);
    }
}
