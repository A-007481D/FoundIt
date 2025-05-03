<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\UserSessionService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CheckSessionStatus
{
    protected $userSessionService;

    public function __construct(UserSessionService $userSessionService)
    {
        $this->userSessionService = $userSessionService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip check for non-authenticated routes
        if (!auth('api')->check()) {
            return $next($request);
        }

        try {
            // Get token from request
            $token = JWTAuth::parseToken()->getToken()->get();
            
            // Check if this session is active
            $isSessionActive = $this->userSessionService->isTokenSessionActive($token);
            
            if (!$isSessionActive) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your session has been terminated by an administrator',
                    'session_terminated' => true
                ], 401);
            }
            
            // Update the last activity time
            $this->userSessionService->updateSessionActivity($token);
            
            return $next($request);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token has expired',
                'session_expired' => true
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid',
                'session_invalid' => true
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Session validation error',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
