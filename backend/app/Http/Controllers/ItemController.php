<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    protected ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Display a listing of the items.
     */
    public function index(Request $request)
    {
        $query = Item::with(['user', 'category'])->where('status', 'active')->where('visible', true);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

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

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $items = $query->orderBy('created_at', 'desc')
                      ->paginate($request->per_page ?? 10);

        return response()->json($items);
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $item = $this->itemService->create(
            $request->validated(),
            $request->file('image'),
            Auth::id()
        );
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
    public function update(UpdateItemRequest $request, $id)
    {
        $item = $this->itemService->update(
            $id,
            $request->validated(),
            $request->file('image'),
            Auth::id()
        );
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
        $item = $this->itemService->archive($id, Auth::id());
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
        $item = $this->itemService->restore($id, Auth::id());
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
