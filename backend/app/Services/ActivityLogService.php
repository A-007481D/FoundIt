<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivityLogService
{
    /**
     * Log user activity
     *
     * @param string $action The action performed
     * @param string|null $entityType The type of entity
     * @param int|null $entityId The ID of the entity
     * @param array|null $metadata Additional data
     * @param int|null $userId User ID (if not authenticated, provide manually)
     * @param string|null $category The category for this activity log
     * @param Request|null $request The HTTP request
     * @return ActivityLog|null The created log entry or null if creation fails
     */
    public function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?array $metadata = null,
        ?int $userId = null,
        ?string $category = null,
        ?Request $request = null
    ): ?ActivityLog
    {
        // Skip logging if no user is authenticated and no user ID is provided
        if (!$userId && !auth()->check()) {
            return null;
        }
        
        // Use provided user ID or get from auth
        $userId = $userId ?? auth()->id();

        // Get request data if not provided
        if (!$request && request()) {
            $request = request();
        }
        
        // Get IP address and user agent from request if available
        $ipAddress = null;
        $userAgent = null;
        if ($request) {
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();
        }
        
        // Determine the appropriate category if not provided
        if (!$category) {
            $category = $this->determineCategory($action, $entityType);
        }
        
        // Create activity log
        return ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'metadata' => $metadata,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'category' => $category,
        ]);
    }
    
    /**
     * Determine the appropriate category for an activity log based on action and entity type
     *
     * @param string $action The action performed
     * @param string|null $entityType The entity type
     * @return string The determined category
     */
    protected function determineCategory(string $action, ?string $entityType): string
    {
        // Authentication related actions
        if (in_array($action, ['login', 'login_failed', 'logout', 'password_reset', 'password_reset_request'])) {
            return 'authentication';
        }
        
        if (strpos($action, 'session') !== false) {
            return 'session_management';
        }
        
        if ($entityType === 'item' || $entityType === 'lost_item' || $entityType === 'found_item') {
            return 'item_management';
        }
        
        if ($entityType === 'user' || strpos($action, 'user') !== false) {
            return 'user_management';
        }
        
        if ($entityType === 'report' || strpos($action, 'report') !== false) {
            return 'reporting';
        }
        
        // Default category
        return 'system';
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
        
        if (isset($filters['category']) && $filters['category'] !== 'all') {
            $query->where('category', $filters['category']);
        }
        
        if (isset($filters['from_date'])) {
            $query->where('created_at', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->where('created_at', '<=', $filters['to_date']);
        }
        
        $query->latest();
        
        return $query->with('user')->paginate($perPage);
    }
    
    /**
     * Get available log categories
     * 
     * @return array
     */
    public function getLogCategories()
    {
        return [
            'authentication' => 'Login/Logout Activities',
            'item_management' => 'Item Related Activities',
            'reporting' => 'Report Related Activities',
            'user_management' => 'User Management Activities',
            'session_management' => 'Session Management',
            'system' => 'System Activities'
        ];
    }
}
