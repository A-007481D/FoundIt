<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Get all items with pagination
     */
    public function index(Request $request)
    {
        $query = Item::with(['user', 'category', 'reports' => function($query) {
            $query->where('status', 'pending');
        }]);
        
        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        // Apply type filter
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        // Apply status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Apply visibility filter
        if ($request->has('visibility') && in_array($request->visibility, ['visible', 'hidden'])) {
            $query->where('visible', $request->visibility === 'visible');
        }
        
        // Get paginated results
        $items = $query->orderBy('created_at', 'desc')
                      ->paginate($request->per_page ?? 10);
                      
        // Include report counts
        $items->getCollection()->transform(function ($item) {
            $item->report_count = $item->reports->count();
            return $item;
        });
        
        return response()->json($items);
    }

    /**
     * Get a specific item
     */
    public function show($id)
    {
        $item = Item::with(['user', 'category', 'reports'])->findOrFail($id);
        
        return response()->json($item);
    }

    /**
     * Update an item's status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,archived,reported,deleted',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item = Item::findOrFail($id);
        $item->status = $request->status;
        $item->save();

        return response()->json([
            'message' => 'Item status updated successfully',
            'item' => $item
        ]);
    }

    /**
     * Update item visibility
     */
    public function updateVisibility(Request $request, $id)
    {
        try {
            $item = Item::findOrFail($id);
            
            $visible = $request->boolean('visible');
            $item->visible = $visible;
            
            // If making a reported item visible again, change status to active
            if ($visible && $item->status === 'reported') {
                $item->status = 'active';
            }
            
            $item->save();
            
            return response()->json([
                'message' => $visible ? 'Item is now visible to users' : 'Item is now hidden from users',
                'item' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update item visibility: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Archive an item
     */
    public function archive($id)
    {
        $item = Item::findOrFail($id);
        $item->status = 'archived';
        $item->save();

        return response()->json([
            'message' => 'Item archived successfully',
            'item' => $item
        ]);
    }

    /**
     * Delete an item (soft delete)
     */
    public function delete(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item = Item::findOrFail($id);
        
        // Clean up the item's image if it exists
        if ($item->image) {
            $imagePath = str_replace(asset('storage/'), '', $item->image);
            Storage::disk('public')->delete($imagePath);
        }
        
        $item->status = 'deleted';
        $item->save();
        $item->delete(); // Soft delete

        // If this was from a report, resolve any pending reports against this item
        if ($request->has('report_id')) {
            $report = Report::find($request->report_id);
            if ($report && $report->status === 'pending') {
                $report->status = 'resolved';
                $report->resolution = $request->reason ?? 'Item was deleted';
                $report->save();
            }
        }

        return response()->json([
            'message' => 'Item deleted successfully'
        ]);
    }

    /**
     * Get item statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total' => Item::count(),
            'active' => Item::where('status', 'active')->count(),
            'archived' => Item::where('status', 'archived')->count(),
            'reported' => Item::where('status', 'reported')->count(),
            'lost' => Item::where('type', 'lost')->count(),
            'found' => Item::where('type', 'found')->count(),
            'recent_reported' => Item::where('status', 'reported')
                                    ->with(['user', 'reports'])
                                    ->orderBy('updated_at', 'desc')
                                    ->take(5)
                                    ->get(),
            'recent' => Item::with('user')->orderBy('created_at', 'desc')->take(5)->get(),
        ];

        return response()->json($stats);
    }
}
