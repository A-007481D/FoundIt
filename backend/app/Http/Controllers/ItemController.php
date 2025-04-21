<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index(Request $request)
    {
        $query = Item::with(['user', 'category'])->where('status', 'active');
        
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
        
        // Apply category filter
        if ($request->has('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }
        
        // Apply date filter
        if ($request->has('date_from')) {
            $query->where(function($q) use ($request) {
                $q->where('lost_date', '>=', $request->date_from)
                  ->orWhere('found_date', '>=', $request->date_from);
            });
        }
        
        if ($request->has('date_to')) {
            $query->where(function($q) use ($request) {
                $q->where('lost_date', '<=', $request->date_to)
                  ->orWhere('found_date', '<=', $request->date_to);
            });
        }
        
        // Get paginated results
        $items = $query->orderBy('created_at', 'desc')
                      ->paginate($request->per_page ?? 10);
        
        return response()->json($items);
    }

    /**
     * Display a listing of the user's items.
     */
    public function userItems(Request $request)
    {
        $query = Item::with(['category'])->where('user_id', Auth::id());
        
        // Apply status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Apply type filter
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        // Get paginated results
        $items = $query->orderBy('created_at', 'desc')
                      ->paginate($request->per_page ?? 10);
        
        return response()->json($items);
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:lost,found',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048', // max 2MB
            'lost_date' => 'required_if:type,lost|date',
            'found_date' => 'required_if:type,found|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        // Create the item
        $item = new Item();
        $item->title = $request->title;
        $item->description = $request->description;
        $item->type = $request->type;
        $item->status = 'active';
        $item->location = $request->location;
        $item->image = $imagePath ? asset('storage/' . $imagePath) : null;
        $item->user_id = Auth::id();
        $item->category_id = $request->category_id;
        
        // Set dates based on type
        if ($request->type === 'lost') {
            $item->lost_date = $request->lost_date;
            // Use current date as default instead of null for found_date
            $item->found_date = $request->found_date ?? now();
        } else {
            $item->found_date = $request->found_date;
            // Use current date as default instead of null for lost_date
            $item->lost_date = $request->lost_date ?? now();
        }
        
        $item->save();

        return response()->json([
            'message' => 'Item created successfully',
            'item' => $item
        ], 201);
    }

    /**
     * Display the specified item.
     */
    public function show($id)
    {
        $item = Item::with(['user', 'category'])->findOrFail($id);
        
        // Check if item is active or belongs to the authenticated user
        if ($item->status !== 'active' && $item->user_id !== Auth::id()) {
            return response()->json(['message' => 'Item not found or not accessible'], 404);
        }
        
        return response()->json($item);
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        
        // Check if the item belongs to the authenticated user
        if ($item->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized to update this item'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'location' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image' => 'nullable|image|max:2048', // max 2MB
            'lost_date' => 'sometimes|required_if:type,lost|date',
            'found_date' => 'sometimes|required_if:type,found|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if exists
            if ($item->image) {
                $oldImagePath = str_replace(asset('storage/'), '', $item->image);
                Storage::disk('public')->delete($oldImagePath);
            }
            
            $imagePath = $request->file('image')->store('items', 'public');
            $item->image = asset('storage/' . $imagePath);
        }

        // Update the item
        if ($request->has('title')) $item->title = $request->title;
        if ($request->has('description')) $item->description = $request->description;
        if ($request->has('location')) $item->location = $request->location;
        if ($request->has('category_id')) $item->category_id = $request->category_id;
        
        // Update dates based on type
        if ($item->type === 'lost' && $request->has('lost_date')) {
            $item->lost_date = $request->lost_date;
        } elseif ($item->type === 'found' && $request->has('found_date')) {
            $item->found_date = $request->found_date;
        }
        
        $item->save();

        return response()->json([
            'message' => 'Item updated successfully',
            'item' => $item
        ]);
    }

    /**
     * Archive the specified item.
     */
    public function archive($id)
    {
        $item = Item::findOrFail($id);
        
        // Check if the item belongs to the authenticated user
        if ($item->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized to archive this item'], 403);
        }
        
        $item->status = 'archived';
        $item->save();

        return response()->json([
            'message' => 'Item archived successfully',
            'item' => $item
        ]);
    }

    /**
     * Restore an archived item.
     */
    public function restore($id)
    {
        $item = Item::findOrFail($id);
        
        // Check if the item belongs to the authenticated user
        if ($item->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized to restore this item'], 403);
        }
        
        // Check if the item is archived
        if ($item->status !== 'archived') {
            return response()->json(['message' => 'Only archived items can be restored'], 400);
        }
        
        $item->status = 'active';
        $item->save();

        return response()->json([
            'message' => 'Item restored successfully',
            'item' => $item
        ]);
    }

    /**
     * Get all categories for item creation/filtering.
     */
    public function getCategories()
    {
        $categories = Category::all();
        
        return response()->json($categories);
    }
}
