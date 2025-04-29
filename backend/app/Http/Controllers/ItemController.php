<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        // If keyword search, use Laravel Scout / Meili for real-time full-text
        if ($request->filled('search')) {
            $search = $request->search;
            $raw = Item::search($search)->raw();
            $hits = $raw['hits'] ?? [];
            $ids = collect($hits)->pluck('id')->all();
            if (empty($ids)) {
                return response()->json([ 'data'=>[], 'current_page'=>1, 'last_page'=>1, 'per_page'=>$request->per_page??10, 'total'=>0 ]);
            }
            $builder = Item::with(['user','category'])
                ->whereIn('id',$ids)
                ->where('status','active')->where('visible',true);
            // Apply filters
            if ($request->filled('location')) {
                $builder->where('location','like',"%{$request->location}%");
            }
            if ($request->has('type') && $request->type!=='all') {
                $builder->where('type',$request->type);
            }
            if ($request->has('category_ids')) {
                $cats = explode(',', $request->category_ids);
                $builder->whereIn('category_id',$cats);
            }
            if ($request->has('date_filter') && $request->date_filter!=='anytime') {
                switch($request->date_filter) {
                    case 'today': $dateFrom=Carbon::today(); break;
                    case 'this-week': $dateFrom=Carbon::now()->startOfWeek(); break;
                    case 'this-month': $dateFrom=Carbon::now()->startOfMonth(); break;
                    default: $dateFrom=null;
                }
                if (isset($dateFrom)) {
                    $builder->where(function($q) use($dateFrom) {
                        $q->where('lost_date','>=',$dateFrom)->orWhere('found_date','>=',$dateFrom);
                    });
                }
            }
            if ($request->has('date_to')) {
                $builder->where(function($q) use($request) {
                    $q->where('lost_date','<=',$request->date_to)->orWhere('found_date','<=',$request->date_to);
                });
            }
            // preserve Meili order
            $builder->orderByRaw('FIELD(id,'.implode(',',$ids).')');
            $items = $builder->paginate($request->per_page??10);
            return response()->json($items);
        }

        $query = Item::with(['user', 'category'])->where('status', 'active')->where('visible', true);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Location filter (partial match)
        if ($request->has('location') && $request->location) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Category filter: support multiple or single selection
        if ($request->has('category_ids')) {
            $ids = explode(',', $request->category_ids);
            $query->whereIn('category_id', $ids);
        } elseif ($request->has('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        // Date filter by predefined periods or explicit range
        if ($request->has('date_filter') && $request->date_filter !== 'anytime') {
            switch ($request->date_filter) {
                case 'today':
                    $dateFrom = Carbon::today();
                    break;
                case 'this-week':
                    $dateFrom = Carbon::now()->startOfWeek();
                    break;
                case 'this-month':
                    $dateFrom = Carbon::now()->startOfMonth();
                    break;
                default:
                    $dateFrom = null;
            }
            if (isset($dateFrom)) {
                $query->where(function ($q) use ($dateFrom) {
                    $q->where('lost_date', '>=', $dateFrom)
                      ->orWhere('found_date', '>=', $dateFrom);
                });
            }
        } elseif ($request->has('date_from')) {
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

        // If this is a "found" item with an image, process it for the item detective
        if ($item->status === 'found' && $request->hasFile('image')) {
            $this->processItemImageFeatures($item, $request->file('image'));
        }

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

        // If item status is "found" and there's a new image, process for item detective
        if ($item->status === 'found' && $request->hasFile('image')) {
            $this->processItemImageFeatures($item, $request->file('image'));
        }

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

    /**
     * Process and store image features for the Item Detective
     *
     * @param \App\Models\Item $item
     * @param \Illuminate\Http\UploadedFile $image
     * @return void
     */
    private function processItemImageFeatures($item, $image)
    {
        try {
            // Get image path from the item (assuming it's already been stored)
            $imagePath = $item->image_path;
            
            // Basic image analysis for color detection
            $colorInfo = $this->analyzeImageColor($image);
            
            // Create or update the image features record
            $itemImageFeature = \App\Models\ItemImageFeature::updateOrCreate(
                ['item_id' => $item->id],
                [
                    'image_path' => $imagePath,
                    'category' => $item->category,
                    'color' => $colorInfo['color'],
                    // Feature vector and classifications will be populated by the frontend
                    // when using the Item Detective feature
                ]
            );
            
            // Log the successful processing
            \Illuminate\Support\Facades\Log::info("Processed image features for item #{$item->id}");
            
        } catch (\Exception $e) {
            // Just log the error but don't fail the request
            \Illuminate\Support\Facades\Log::error("Error processing image features: " . $e->getMessage());
        }
    }

    /**
     * Basic color analysis from the image
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @return array
     */
    private function analyzeImageColor($image)
    {
        try {
            // Create a temporary file to work with
            $tempPath = $image->getRealPath();
            
            // Load the image with GD library
            $imageData = getimagesize($tempPath);
            $mime = $imageData['mime'];
            
            switch ($mime) {
                case 'image/jpeg':
                    $img = imagecreatefromjpeg($tempPath);
                    break;
                case 'image/png':
                    $img = imagecreatefrompng($tempPath);
                    break;
                case 'image/gif':
                    $img = imagecreatefromgif($tempPath);
                    break;
                default:
                    throw new \Exception("Unsupported image type: $mime");
            }
            
            // Resize to smaller dimensions for faster processing
            $width = imagesx($img);
            $height = imagesy($img);
            $newWidth = 150;
            $newHeight = floor($height * ($newWidth / $width));
            $resizedImg = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG
            if ($mime == 'image/png') {
                imagealphablending($resizedImg, false);
                imagesavealpha($resizedImg, true);
            }
            
            imagecopyresampled($resizedImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            
            // Count colors
            $colorCounts = [];
            for ($x = 0; $x < $newWidth; $x++) {
                for ($y = 0; $y < $newHeight; $y++) {
                    $rgb = imagecolorat($resizedImg, $x, $y);
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    
                    // Skip if fully transparent
                    if ($mime == 'image/png') {
                        $alpha = ($rgb & 0x7F000000) >> 24;
                        if ($alpha == 127) continue;
                    }
                    
                    // Simple color classification
                    $color = $this->classifyColor($r, $g, $b);
                    if (!isset($colorCounts[$color])) {
                        $colorCounts[$color] = 0;
                    }
                    $colorCounts[$color]++;
                }
            }
            
            // Free memory
            imagedestroy($img);
            imagedestroy($resizedImg);
            
            // Get the dominant color
            arsort($colorCounts);
            $dominantColor = key($colorCounts);
            
            return [
                'color' => $dominantColor,
                'color_distribution' => $colorCounts
            ];
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error analyzing image color: ' . $e->getMessage());
            return [
                'color' => 'Unknown',
                'color_distribution' => []
            ];
        }
    }

    /**
     * Classify RGB values into named colors
     *
     * @param int $r
     * @param int $g
     * @param int $b
     * @return string
     */
    private function classifyColor($r, $g, $b)
    {
        // Check for grayscale first
        if (abs($r - $g) < 15 && abs($g - $b) < 15 && abs($r - $b) < 15) {
            $brightness = ($r + $g + $b) / 3;
            if ($brightness < 50) return 'Black';
            if ($brightness < 120) return 'Gray';
            if ($brightness < 220) return 'Gray';
            return 'White';
        }
        
        // Check for main colors
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        
        // Check if it's a vivid color
        if ($max - $min < 50) {
            return "Muted";
        }
        
        if ($r > 200 && $g < 100 && $b < 100) return 'Red';
        if ($r > 200 && $g > 150 && $b < 100) return 'Orange';
        if ($r > 200 && $g > 200 && $b < 100) return 'Yellow';
        if ($r < 100 && $g > 150 && $b < 100) return 'Green';
        if ($r < 100 && $g > 100 && $b > 200) return 'Blue';
        if ($r > 150 && $g < 100 && $b > 150) return 'Purple';
        if ($r > 200 && $g < 100 && $b > 150) return 'Pink';
        if ($r > 150 && $g > 100 && $b < 100) return 'Brown';
        
        // Default fallback
        return 'Multicolored';
    }
}
