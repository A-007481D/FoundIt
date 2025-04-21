<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Get all items with pagination
     */
    public function index(Request $request)
    {
        $query = Item::with('user');
        
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
        
        // Get paginated results
        $items = $query->orderBy('created_at', 'desc')
                      ->paginate($request->per_page ?? 10);
        
        return response()->json($items);
    }

    /**
     * Get a specific item
     */
    public function show($id)
    {
        $item = Item::with('user', 'category')->findOrFail($id);
        
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
    public function delete($id)
    {
        $item = Item::findOrFail($id);
        $item->status = 'deleted';
        $item->save();
        $item->delete(); // Soft delete

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
            'recent' => Item::with('user')->orderBy('created_at', 'desc')->take(5)->get(),
        ];

        return response()->json($stats);
    }
}
