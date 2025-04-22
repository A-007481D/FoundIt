<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Get all reports with pagination
     */
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reportable']);
        
        // Apply search filter
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%");
            });
        }
        
        // Apply type filter
        if ($request->has('type') && $request->type !== 'all' && $request->type !== '') {
            if ($request->type === 'user') {
                $query->where('reportable_type', User::class);
            } elseif ($request->type === 'item') {
                $query->where('reportable_type', Item::class);
            }
        }
        
        // Apply status filter
        if ($request->has('status') && $request->status !== 'all' && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Get paginated results
        $reports = $query->orderBy('created_at', 'desc')
                        ->paginate($request->per_page ?? 10);
        
        // Transform the response to include subject details
        $reports->getCollection()->transform(function ($report) {
            $subject = $report->reportable;
            
            // Add subject data based on type
            if ($report->reportable_type === User::class) {
                $report->type = 'user';
                $report->subject = [
                    'id' => $subject->id,
                    'name' => $subject->firstname . ' ' . $subject->lastname,
                    'email' => $subject->email,
                    'image' => null // Add user image if available
                ];
            } elseif ($report->reportable_type === Item::class) {
                $report->type = 'item';
                $report->subject = [
                    'id' => $subject->id,
                    'title' => $subject->title,
                    'image' => $subject->image,
                    'status' => $subject->status,
                    'visible' => $subject->visible
                ];
            }
            
            // Remove the reportable relationship to avoid duplication
            unset($report->reportable);
            
            return $report;
        });
        
        return response()->json($reports);
    }

    /**
     * Get a specific report
     */
    public function show($id)
    {
        $report = Report::with(['reporter', 'reportable'])->findOrFail($id);
        
        // Add subject data based on type
        if ($report->reportable_type === User::class) {
            $report->type = 'user';
            $report->subject = [
                'id' => $report->reportable->id,
                'name' => $report->reportable->firstname . ' ' . $report->reportable->lastname,
                'email' => $report->reportable->email,
                'image' => null // Add user image if available
            ];
        } elseif ($report->reportable_type === Item::class) {
            $report->type = 'item';
            $report->subject = [
                'id' => $report->reportable->id,
                'title' => $report->reportable->title,
                'image' => $report->reportable->image
            ];
        }
        
        // Remove the reportable relationship to avoid duplication
        unset($report->reportable);
        
        return response()->json($report);
    }

    /**
     * Update a report's status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,resolved,dismissed',
            'resolution' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $report = Report::findOrFail($id);
        $report->status = $request->status;
        
        if ($request->has('resolution')) {
            $report->resolution = $request->resolution;
        }
        
        $report->save();

        return response()->json([
            'message' => 'Report status updated successfully',
            'report' => $report
        ]);
    }

    /**
     * Resolve a report
     */
    public function resolve(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'resolution' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $report = Report::findOrFail($id);
        $report->status = 'resolved';
        $report->resolution = $request->resolution;
        $report->save();
        
        // If this is an item report, make the item visible again but keep the reported status
        if ($report->reportable_type === Item::class) {
            $item = Item::find($report->reportable_id);
            if ($item) {
                $item->visible = true; // Make item visible again
                $item->save();
            }
        }

        return response()->json([
            'message' => 'Report resolved successfully',
            'report' => $report
        ]);
    }

    /**
     * Dismiss a report
     */
    public function dismiss(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'resolution' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $report = Report::findOrFail($id);
        $report->status = 'dismissed';
        $report->resolution = $request->resolution;
        $report->save();
        
        // If this is an item report, make the item visible again but keep the reported status
        if ($report->reportable_type === Item::class) {
            $item = Item::find($report->reportable_id);
            if ($item) {
                $item->visible = true; // Make item visible again
                $item->save();
            }
        }

        return response()->json([
            'message' => 'Report dismissed successfully',
            'report' => $report
        ]);
    }

    /**
     * Create a new report
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
            'reportable_id' => 'required|integer',
            'reportable_type' => 'required|in:user,item',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Map the reportable_type to the correct model class
        $reportableType = $request->reportable_type === 'user' ? User::class : Item::class;
        
        // Check if the reportable entity exists
        if ($request->reportable_type === 'user') {
            $reportable = User::find($request->reportable_id);
        } else {
            $reportable = Item::find($request->reportable_id);
        }
        
        if (!$reportable) {
            return response()->json(['error' => 'The reported ' . $request->reportable_type . ' does not exist.'], 404);
        }

        // Create the report
        $report = new Report();
        $report->reason = $request->reason;
        $report->details = $request->details;
        $report->status = 'pending';
        $report->reporter_id = auth()->id();
        $report->reportable_id = $request->reportable_id;
        $report->reportable_type = $reportableType;
        $report->save();

        // If it's an item report, update the item status and hide it
        if ($request->reportable_type === 'item') {
            $reportable->status = 'reported';
            $reportable->visible = false; // Hide the item until the report is resolved
            $reportable->save();
        }

        return response()->json([
            'message' => 'Report submitted successfully',
            'report' => $report
        ], 201);
    }

    /**
     * Get report statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'dismissed' => Report::where('status', 'dismissed')->count(),
            'user_reports' => Report::where('reportable_type', User::class)->count(),
            'item_reports' => Report::where('reportable_type', Item::class)->count(),
            'recent' => Report::with('reporter')->orderBy('created_at', 'desc')->take(5)->get(),
        ];

        return response()->json($stats);
    }
}
