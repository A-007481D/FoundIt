<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserSessionService;
use App\Models\UserSession;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserSessionController extends Controller
{
    protected UserSessionService $sessionService;
    protected ActivityLogService $activityLogService;
    
    public function __construct(UserSessionService $sessionService, ActivityLogService $activityLogService)
    {
        $this->sessionService = $sessionService;
        $this->activityLogService = $activityLogService;
        $this->middleware(['auth:api', 'admin']);
    }
    
    /**
     * Get all active sessions with filtering
     */
    public function index(Request $request)
    {
        $filters = $this->getFiltersFromRequest($request);
        $perPage = $request->per_page ?? 15;
        
        $sessions = $this->sessionService->getAllActiveSessions($filters, $perPage);
        
        return response()->json($sessions);
    }
    
    /**
     * Get active sessions for a specific user
     */
    public function userSessions(Request $request, $userId)
    {
        // Verify user exists
        $user = User::findOrFail($userId);
        
        $sessions = $this->sessionService->getUserActiveSessions($userId);
        
        return response()->json([
            'user' => $user->only(['id', 'firstname', 'lastname', 'email', 'role', 'status']),
            'sessions' => $sessions
        ]);
    }
    
    /**
     * Terminate a specific session
     */
    public function terminateSession(Request $request, $sessionId)
    {
        $session = UserSession::with('user')->findOrFail($sessionId);
        
        // Check if session is already terminated
        if (!$session->is_active) {
            return response()->json([
                'message' => 'Session is already terminated'
            ], 400);
        }
        
        // Terminate session
        $terminated = $this->sessionService->terminateSession($sessionId);
        
        if ($terminated) {
            // Log the action
            $this->activityLogService->log(
                'session_terminated',
                'UserSession',
                $session->id,
                [
                    'user_id' => $session->user_id,
                    'user_email' => $session->user->email,
                    'terminated_by' => auth('api')->id()
                ],
                $request
            );
            
            return response()->json([
                'message' => 'Session terminated successfully',
                'session' => $session
            ]);
        }
        
        return response()->json([
            'message' => 'Failed to terminate session'
        ], 500);
    }
    
    /**
     * Terminate all active sessions for a user
     */
    public function terminateUserSessions(Request $request, $userId)
    {
        // Verify user exists
        $user = User::findOrFail($userId);
        
        // Terminate all active sessions
        $count = $this->sessionService->terminateAllUserSessions($userId);
        
        // Log the action
        $this->activityLogService->log(
            'all_sessions_terminated',
            'User',
            $userId,
            [
                'count' => $count,
                'user_email' => $user->email,
                'terminated_by' => auth('api')->id()
            ],
            $request
        );
        
        return response()->json([
            'message' => "All sessions terminated successfully for user {$user->email}",
            'count' => $count
        ]);
    }
    
    /**
     * Cleanup expired sessions (maintenance task)
     */
    public function cleanupExpiredSessions(Request $request)
    {
        $count = $this->sessionService->cleanupExpiredSessions();
        
        // Log the action
        $this->activityLogService->log(
            'sessions_cleanup',
            null,
            null,
            [
                'count' => $count,
                'initiated_by' => auth('api')->id()
            ],
            $request
        );
        
        return response()->json([
            'message' => 'Expired sessions cleaned up successfully',
            'count' => $count
        ]);
    }
    
    /**
     * Extract filters from request
     */
    private function getFiltersFromRequest(Request $request)
    {
        $filters = [];
        
        if ($request->has('user_id') && $request->user_id !== 'all') {
            $filters['user_id'] = $request->user_id;
        }
        
        if ($request->has('from_date')) {
            $filters['from_date'] = $request->from_date;
        }
        
        if ($request->has('to_date')) {
            $filters['to_date'] = $request->to_date;
        }
        
        return $filters;
    }
}
