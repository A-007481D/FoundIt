<?php

namespace App\Services;

use App\Models\UserSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserSessionService
{
    /**
     * Create a new session for a user
     *
     * @param int $userId The user ID
     * @param string $token The JWT token
     * @param Request $request The HTTP request
     * @return UserSession The created session
     */
    public function createSession(int $userId, string $token, Request $request): UserSession
    {
        $deviceInfo = $this->parseUserAgent($request->userAgent());
        
        // Get TTL from JWT config and convert to Carbon instance
        $ttl = auth('api')->factory()->getTTL() * 60;
        $expiresAt = Carbon::now()->addSeconds($ttl);
        
        return UserSession::create([
            'user_id' => $userId,
            'token' => hash('sha256', $token),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_info' => $deviceInfo,
            'last_activity_at' => Carbon::now(),
            'expires_at' => $expiresAt,
            'is_active' => true,
        ]);
    }
    
    /**
     * Get all active sessions for a user
     *
     * @param int $userId The user ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserActiveSessions(int $userId)
    {
        return UserSession::forUser($userId)
                          ->active()
                          ->orderBy('last_activity_at', 'desc')
                          ->get();
    }
    
    /**
     * Get all active sessions (for admin)
     *
     * @param array $filters Filtering options
     * @param int $perPage Items per page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllActiveSessions(array $filters = [], int $perPage = 15)
    {
        $query = UserSession::with('user')->active();
        
        if (isset($filters['user_id']) && $filters['user_id'] !== 'all') {
            $query->forUser($filters['user_id']);
        }
        
        if (isset($filters['from_date'])) {
            $query->where('last_activity_at', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->where('last_activity_at', '<=', $filters['to_date']);
        }
        
        return $query->orderBy('last_activity_at', 'desc')
                     ->paginate($perPage);
    }
    
    /**
     * Terminate a specific session
     *
     * @param int $sessionId The session ID
     * @return bool Whether the session was terminated successfully
     */
    public function terminateSession(int $sessionId): bool
    {
        $session = UserSession::findOrFail($sessionId);
        return $session->terminate();
    }
    
    /**
     * Terminate all sessions for a user
     *
     * @param int $userId The user ID
     * @return int Number of sessions terminated
     */
    public function terminateAllUserSessions(int $userId): int
    {
        return UserSession::forUser($userId)
                          ->active()
                          ->update(['is_active' => false]);
    }
    
    /**
     * Update the last activity time for a session
     *
     * @param string $token The JWT token
     * @return bool Whether the session was updated successfully
     */
    public function updateSessionActivity(string $token): bool
    {
        $hashedToken = hash('sha256', $token);
        $session = UserSession::where('token', $hashedToken)->active()->first();
        
        if ($session) {
            return $session->updateLastActivity();
        }
        
        return false;
    }
    
    /**
     * Clean up expired sessions
     *
     * @return int Number of sessions cleaned up
     */
    public function cleanupExpiredSessions(): int
    {
        return UserSession::where('expires_at', '<', Carbon::now())
                          ->where('is_active', true)
                          ->update(['is_active' => false]);
    }
    
    /**
     * Parse user agent to extract device info
     *
     * @param string|null $userAgent The user agent string
     * @return string A simple device description
     */
    private function parseUserAgent(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown device';
        }
        
        $deviceInfo = 'Unknown device';
        
        if (preg_match('/(android|iphone|ipad|ipod|blackberry|windows phone)/i', $userAgent)) {
            $deviceInfo = 'Mobile device';
            
            if (stripos($userAgent, 'android') !== false) {
                $deviceInfo = 'Android device';
            } elseif (stripos($userAgent, 'iphone') !== false || 
                      stripos($userAgent, 'ipad') !== false || 
                      stripos($userAgent, 'ipod') !== false) {
                $deviceInfo = 'iOS device';
            }
        }
        elseif (preg_match('/(windows|macintosh|linux|ubuntu)/i', $userAgent)) {
            $deviceInfo = 'Desktop';
            
            if (stripos($userAgent, 'windows') !== false) {
                $deviceInfo = 'Windows PC';
            } elseif (stripos($userAgent, 'macintosh') !== false) {
                $deviceInfo = 'Mac';
            } elseif (stripos($userAgent, 'linux') !== false || 
                      stripos($userAgent, 'ubuntu') !== false) {
                $deviceInfo = 'Linux';
            }
        }
        
        $browser = 'Unknown browser';
        if (stripos($userAgent, 'chrome') !== false) {
            $browser = 'Chrome';
        } elseif (stripos($userAgent, 'firefox') !== false) {
            $browser = 'Firefox';
        } elseif (stripos($userAgent, 'safari') !== false) {
            $browser = 'Safari';
        } elseif (stripos($userAgent, 'edge') !== false) {
            $browser = 'Edge';
        } elseif (stripos($userAgent, 'opera') !== false) {
            $browser = 'Opera';
        }
        
        return $deviceInfo . ' / ' . $browser;
    }
}
