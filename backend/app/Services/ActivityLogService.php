<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivityLogService
{
    /**
     * Log an activity
     *
     * @param string $action The action performed (login, create, update, delete, etc.)
     * @param string|null $entityType The type of entity (Item, User, etc.)
     * @param int|null $entityId The ID of the entity
     * @param array|null $metadata Additional information about the activity
     * @param Request|null $request The HTTP request (for IP and User-Agent)
     * @return ActivityLog The created activity log
     */
    public function log(
        string $action, 
        ?string $entityType = null, 
        ?int $entityId = null, 
        ?array $metadata = null, 
        ?Request $request = null
    ): ?ActivityLog {
        $userId = Auth::id();
        
        if (!$userId) {
            // If no authenticated user, try to get user ID from metadata if provided
            $userId = $metadata['user_id'] ?? null;
        }
        
        // If user_id is still null, we can't create the log (would violate DB constraint)
        if ($userId === null) {
            // Return null instead of creating a log when user_id is null
            return null;
        }
        
        // If request is provided, get IP and user agent
        $ipAddress = null;
        $userAgent = null;
        if ($request) {
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();
        }
        
        return ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'metadata' => $metadata,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
    
    /**
     * Get activities for a specific user
     *
     * @param int $userId
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserActivities(int $userId, array $filters = [], int $perPage = 15)
    {
        $query = ActivityLog::forUser($userId);
        
        if (isset($filters['action']) && $filters['action'] !== 'all') {
            $query->ofAction($filters['action']);
        }
        
        if (isset($filters['entity_type']) && $filters['entity_type'] !== 'all') {
            $query->forEntity($filters['entity_type']);
        }
        
        if (isset($filters['from_date'])) {
            $query->where('created_at', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->where('created_at', '<=', $filters['to_date']);
        }
        
        return $query->with('user')
                     ->orderBy('created_at', 'desc')
                     ->paginate($perPage);
    }
    
    /**
     * Get all activities with filtering options
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllActivities(array $filters = [], int $perPage = 15)
    {
        $query = ActivityLog::query();
        
        if (isset($filters['user_id']) && $filters['user_id'] !== 'all') {
            $query->forUser($filters['user_id']);
        }
        
        if (isset($filters['action']) && $filters['action'] !== 'all') {
            $query->ofAction($filters['action']);
        }
        
        if (isset($filters['entity_type']) && $filters['entity_type'] !== 'all') {
            $query->forEntity($filters['entity_type']);
        }
        
        if (isset($filters['from_date'])) {
            $query->where('created_at', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->where('created_at', '<=', $filters['to_date']);
        }
        
        return $query->with('user')
                     ->orderBy('created_at', 'desc')
                     ->paginate($perPage);
    }
}
