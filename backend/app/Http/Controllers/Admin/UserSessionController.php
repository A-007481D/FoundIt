<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserSessionService;
use App\Models\UserSession;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SessionTerminated;

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
        
        // Get the user before terminating the session for notification purposes
        $user = $session->user;
        
        // Terminate session
        $terminated = $this->sessionService->terminateSession($sessionId, auth('api')->id());
        
        if ($terminated) {
            // Send notification to user about session termination if possible
            if ($user) {
                try {
                    // Notify the user that their session was terminated
                    // This creates a database notification they'll see on their next login
                    Notification::send($user, new SessionTerminated(auth('api')->user()->firstname));
                } catch (\Exception $e) {
                    // Log the error but continue with the termination process
                    \Log::error('Failed to send session termination notification: ' . $e->getMessage());
                }
            }
            
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
        $count = $this->sessionService->terminateAllUserSessions($userId, auth('api')->id());
        
        // Send notification to user about all sessions being terminated
        try {
            // Notify user that all their sessions were terminated
            // This creates a database notification they'll see on their next login
            $adminName = auth('api')->user()->firstname;
            Notification::send($user, new SessionTerminated($adminName, true));
        } catch (\Exception $e) {
            // Log the error but continue with the termination process
            \Log::error('Failed to send session termination notification: ' . $e->getMessage());
        }
        
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
