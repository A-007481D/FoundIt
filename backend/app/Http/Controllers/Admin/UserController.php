<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Get all users with pagination
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Apply role filter
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        // Apply status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Get paginated results
        $users = $query->orderBy('created_at', 'desc')
                      ->paginate($request->per_page ?? 10);
        
        return response()->json($users);
    }

    /**
     * Get a specific user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Update a user's status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,suspended,banned',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json([
            'message' => 'User status updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Update a user's role
     */
    public function updateRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:user,admin',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return response()->json([
            'message' => 'User role updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Get user statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'banned' => User::where('status', 'banned')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'recent' => User::orderBy('created_at', 'desc')->take(5)->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Ban a user
     */
    public function ban(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);
        $user->status = 'banned';
        $user->banned_reason = $request->reason;
        $user->banned_at = now();
        $user->save();

        // If this was from a report, resolve any pending reports against this user
        if ($request->has('report_id')) {
            $report = Report::find($request->report_id);
            if ($report && $report->status === 'pending') {
                $report->status = 'resolved';
                $report->resolution = $request->reason ?? 'User was banned';
                $report->save();
            }
        }

        return response()->json([
            'message' => 'User banned successfully',
            'user' => $user
        ]);
    }

    /**
     * Suspend a user for a specific number of days
     */
    public function suspend(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|integer|min:1|max:365',
            'reason' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);
        $user->status = 'suspended';
        $user->suspended_reason = $request->reason;
        $user->suspended_at = now();
        $user->suspension_end = Carbon::now()->addDays($request->days);
        $user->save();

        // If this was from a report, resolve any pending reports against this user
        if ($request->has('report_id')) {
            $report = Report::find($request->report_id);
            if ($report && $report->status === 'pending') {
                $report->status = 'resolved';
                $report->resolution = $request->reason ?? "User was suspended for {$request->days} days";
                $report->save();
            }
        }

        return response()->json([
            'message' => "User suspended for {$request->days} days",
            'user' => $user
        ]);
    }
    
    /**
     * Unban a user
     */
    public function unban($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->status !== 'banned') {
            return response()->json(['message' => 'User is not banned'], 400);
        }
        
        $user->status = 'active';
        $user->banned_reason = null;
        $user->banned_at = null;
        $user->save();
        
        // If this was from a report, mark any pending reports against this user as dismissed
        $reports = Report::where('reportable_type', 'user')
            ->where('reportable_id', $user->id)
            ->where('status', 'pending')
            ->get();
            
        foreach ($reports as $report) {
            $report->status = 'dismissed';
            $report->resolution = 'User was unbanned';
            $report->save();
        }

        return response()->json([
            'message' => 'User unbanned successfully',
            'user' => $user
        ]);
    }
}
