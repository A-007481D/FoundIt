<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityLogController extends Controller
{
    protected ActivityLogService $activityLogService;
    
    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
        $this->middleware(['auth:api', 'admin']);
    }
    
    /**
     * Get all activity logs with filtering
     */
    public function index(Request $request)
    {
        $filters = $this->getFiltersFromRequest($request);
        $perPage = $request->per_page ?? 15;
        
        $activities = $this->activityLogService->getAllActivities($filters, $perPage);
        
        return response()->json($activities);
    }
    
    /**
     * Get activity logs for a specific user
     */
    public function userActivities(Request $request, $userId)
    {
        $filters = $this->getFiltersFromRequest($request);
        $perPage = $request->per_page ?? 15;
        
        $activities = $this->activityLogService->getUserActivities($userId, $filters, $perPage);
        
        return response()->json($activities);
    }
    
    /**
     * Get action types for filtering
     */
    public function getActionTypes()
    {
        // Get distinct action types from the database
        $actionTypes = \App\Models\ActivityLog::distinct('action')->pluck('action');
        
        return response()->json([
            'action_types' => $actionTypes
        ]);
    }
    
    /**
     * List entity types used in activity logs
     */
    public function getEntityTypes()
    {
        $entityTypes = \App\Models\ActivityLog::distinct('entity_type')
            ->whereNotNull('entity_type')
            ->pluck('entity_type')
            ->toArray();
        
        return response()->json(['entity_types' => $entityTypes]);
    }
    
    /**
     * Get available log categories
     */
    public function getCategories(ActivityLogService $activityLogService)
    {
        $categories = $activityLogService->getLogCategories();
        
        return response()->json(['categories' => $categories]);
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
        
        if ($request->has('action') && $request->action !== 'all') {
            $filters['action'] = $request->action;
        }
        
        if ($request->has('entity_type') && $request->entity_type !== 'all') {
            $filters['entity_type'] = $request->entity_type;
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
