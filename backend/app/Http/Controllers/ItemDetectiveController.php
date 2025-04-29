<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemImageFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ItemDetectiveController extends Controller
{
    /**
     * Search for visually similar items based on the uploaded image and feature vectors
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:10240', // 10MB limit
                'features' => 'sometimes|json',  // Make features optional
                'classifications' => 'sometimes|json'  // Make classifications optional
            ]);

            // Check if we're running in TensorFlow mode or just backend mode
            $tensorflowMode = $request->has('features') && $request->has('classifications');
            
            if ($tensorflowMode) {
                // Parse the feature vector and classifications from the request
                $featureVector = json_decode($request->features, true);
                $classifications = json_decode($request->classifications, true);
                
                // Extract category from classifications
                $categoryInfo = $this->extractCategoryFromClassifications($classifications);
                $category = $categoryInfo['category'];
            } else {
                // Backend-only mode (no TensorFlow.js features)
                // We'll analyze the image directly using our backend logic
                $category = 'Unknown'; // Default, will be updated from color analysis
                $classifications = []; // Empty classifications
                $featureVector = []; // Empty feature vector
            }
            
            // Extract color from the image - we'll do this in both modes
            $colorInfo = $this->analyzeImageColor($request->file('image'));
            
            // In backend-only mode, try to determine the category from the color
            if (!$tensorflowMode) {
                // Set a general category based on the color (very basic)
                $category = $this->guessCategoryFromColor($colorInfo['color']);
            }
            
            // Get "found" items with feature vectors for comparison
            $itemFeatures = ItemImageFeature::with('item')
                ->whereHas('item', function($query) {
                    $query->where('type', 'found')
                          ->where('status', 'active');
                })
                ->get();

            // Add debug information
            $itemCount = $itemFeatures->count();
            $debugInfo = [
                'item_count' => $itemCount,
                'search_category' => $category,
                'search_color' => $colorInfo['color'],
                'tensorflow_mode' => $tensorflowMode,
                'first_items' => $itemFeatures->take(3)->map(function($feature) {
                    return [
                        'id' => $feature->item_id,
                        'name' => $feature->item ? $feature->item->title : 'Unknown',
                        'category' => $feature->category,
                        'color' => $feature->color
                    ];
                })
            ];

            // If we have no items, return empty results
            if ($itemFeatures->isEmpty()) {
                return response()->json([
                    'results' => [],
                    'category' => $category,
                    'color' => $colorInfo['color'],
                    'brand' => 'Unknown',
                    'debug' => $debugInfo
                ]);
            }

            // Calculate similarity scores - using different approaches based on mode
            if ($tensorflowMode && count($featureVector) > 0) {
                // TensorFlow mode - use feature vectors for comparison
                $matchResults = $this->calculateSimilarityScores($featureVector, $itemFeatures, $category, $colorInfo['color']);
            } else {
                // Backend-only mode - use simpler color and category matching
                $matchResults = $this->calculateSimpleMatchScores($itemFeatures, $category, $colorInfo['color']);
            }

            // Add scoring debug info
            $debugInfo['scores'] = $matchResults->take(5)->map(function($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'match_percentage' => $item['match_percentage'],
                    'color' => $item['color'],
                    'category' => $item['feature1']
                ];
            });

            // Return only the top matches that meet minimum threshold
            $results = $matchResults
                ->filter(function ($item) {
                    return $item['match_percentage'] >= 60; 
                })
                ->take(8) // res limit 
                ->values()
                ->all();

            // If no matches, return an empty array
            if (empty($results)) {
                return response()->json([
                    'results' => [],
                    'category' => $category,
                    'color' => $colorInfo['color'],
                    'brand' => $tensorflowMode ? $this->guessBrand($classifications, $category) : 'Unknown',
                    'debug' => $debugInfo
                ]);
            }

            return response()->json([
                'results' => $results,
                'category' => $category,
                'color' => $colorInfo['color'],
                'brand' => $tensorflowMode ? $this->guessBrand($classifications, $category) : 'Unknown',
                'debug' => $debugInfo
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in item detective search: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to process image',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Extract the main category from TensorFlow classifications
     *
     * @param array $classifications
     * @return array
     */
    private function extractCategoryFromClassifications(array $classifications)
    {
        // Get the top classification
        $topClass = $classifications[0] ?? null;
        
        if (!$topClass) {
            return [
                'category' => 'Unknown',
                'confidence' => 0
            ];
        }
        
        // Clean up the category name (often comes as "backpack, rucksack, ...")
        $category = explode(',', $topClass['className'])[0];
        $confidence = $topClass['probability'];
        
        // Map TensorFlow categories to our system categories
        $categoryMap = [
            'backpack' => 'Bag',
            'rucksack' => 'Bag',
            'purse' => 'Bag',
            'handbag' => 'Bag',
            'wallet' => 'Accessory',
            'cellular telephone' => 'Electronics',
            'cellular phone' => 'Electronics',
            'mobile phone' => 'Electronics',
            'laptop' => 'Electronics',
            'notebook' => 'Electronics',
            'computer' => 'Electronics',
            'iPod' => 'Electronics',
            'remote control' => 'Electronics',
            'camera' => 'Electronics',
            'digital watch' => 'Accessory',
            'analog watch' => 'Accessory',
            'wristwatch' => 'Accessory',
            'watch' => 'Accessory',
            'umbrella' => 'Accessory',
            'sunglasses' => 'Accessory',
            'glasses' => 'Accessory',
            'pen' => 'Accessory',
            'pencil' => 'Accessory',
            'notebook' => 'Accessory',
            'book' => 'Accessory',
            'jersey' => 'Clothing',
            'shirt' => 'Clothing',
            'sweater' => 'Clothing',
            'jacket' => 'Clothing',
            'coat' => 'Clothing',
            'trousers' => 'Clothing',
            'jeans' => 'Clothing',
            'shorts' => 'Clothing',
            'cap' => 'Clothing',
            'hat' => 'Clothing',
            'helmet' => 'Accessory',
            'tie' => 'Clothing',
            'necklace' => 'Accessory',
            'bracelet' => 'Accessory',
            'ring' => 'Accessory',
            'earrings' => 'Accessory',
            'key' => 'Key',
            'keys' => 'Key',
            'keychain' => 'Key',
        ];
        
        // Find matching category or default to Other
        $mappedCategory = 'Other';
        foreach ($categoryMap as $keyword => $mappedValue) {
            if (stripos($category, $keyword) !== false) {
                $mappedCategory = $mappedValue;
                break;
            }
        }
        
        return [
            'category' => $mappedCategory,
            'confidence' => $confidence
        ];
    }
    
    /**
     * Guess the brand based on classifications
     * 
     * @param array $classifications
     * @param string $category
     * @return string
     */
    private function guessBrand(array $classifications, string $category)
    {
        // List of common brands to check against
        $brands = [
            'Apple', 'Samsung', 'Google', 'Sony', 'Nokia', 'Huawei', 'LG', 'Motorola', 
            'Nike', 'Adidas', 'Puma', 'Reebok', 'Under Armour',
            'Gucci', 'Louis Vuitton', 'Prada', 'Coach', 'Michael Kors',
            'Rolex', 'Casio', 'Seiko', 'Timex', 'Fossil', 'Garmin',
            'JanSport', 'The North Face', 'Herschel', 'Fjällräven', 'Samsonite',
            'Ray-Ban', 'Oakley', 'Persol'
        ];
        
        // Check all classifications for brand mentions
        foreach ($classifications as $class) {
            $className = $class['className'];
            
            foreach ($brands as $brand) {
                if (stripos($className, $brand) !== false) {
                    return $brand;
                }
            }
        }
        
        // If no specific brand is found, use a general descriptor based on category
        $categoryBrandMap = [
            'Electronics' => 'Generic Electronics',
            'Accessory' => 'Fashion Accessory',
            'Bag' => 'Fashion Bag',
            'Clothing' => 'Fashion Item',
            'Key' => 'Standard Key',
        ];
        
        return $categoryBrandMap[$category] ?? 'Unknown';
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
            
            // Try to load the image based on mime type
            $img = null;
            switch ($mime) {
                case 'image/jpeg':
                    $img = @imagecreatefromjpeg($tempPath);
                    break;
                case 'image/png':
                    $img = @imagecreatefrompng($tempPath);
                    break;
                case 'image/gif':
                    $img = @imagecreatefromgif($tempPath);
                    break;
                case 'image/webp':
                    if (function_exists('imagecreatefromwebp')) {
                        $img = @imagecreatefromwebp($tempPath);
                    }
                    break;
                default:
                    throw new \Exception("Unsupported image type: $mime");
            }
            
            if (!$img) {
                throw new \Exception("Failed to create image resource from uploaded file");
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
            
            // Count colors with better sampling (sample every other pixel for speed)
            $colorCounts = [];
            $samplingStep = 2; // Sample every 2nd pixel
            
            for ($x = 0; $x < $newWidth; $x += $samplingStep) {
                for ($y = 0; $y < $newHeight; $y += $samplingStep) {
                    $rgb = imagecolorat($resizedImg, $x, $y);
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    
                    // Skip if fully transparent for PNG
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
            // Log error but provide a default color
            Log::error('Error analyzing image color: ' . $e->getMessage());
            
            // Try to determine a reasonable default color
            $defaultColor = 'Unknown';
            
            // If we have any color info from previous attempts, use it
            if (!empty($colorCounts) && is_array($colorCounts)) {
                arsort($colorCounts);
                $defaultColor = key($colorCounts);
            }
            
            return [
                'color' => $defaultColor,
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
    
    /**
     * Calculate similarity scores between the input feature vector and stored features
     *
     * @param array $featureVector
     * @param \Illuminate\Database\Eloquent\Collection $itemFeatures
     * @param string $category
     * @param string $color
     * @return \Illuminate\Support\Collection
     */
    private function calculateSimilarityScores($featureVector, $itemFeatures, $category, $color)
    {
        return $itemFeatures->map(function ($itemFeature) use ($featureVector, $category, $color) {
            $item = $itemFeature->item;
            $storedVector = $itemFeature->feature_vector;
            
            // Basic validation
            if (!is_array($storedVector) || count($storedVector) != count($featureVector)) {
                return [
                    'id' => $item->id,
                    'name' => $item->title,
                    'match_percentage' => 0,
                    'image_url' => $this->getItemImageUrl($item),
                    'location' => $item->location ?? 'Unknown',
                    'found_date' => $item->created_at->format('M j, Y'),
                    'last_seen' => $this->formatLastSeen($item->updated_at),
                    'color' => $itemFeature->color ?? 'Unknown',
                    'feature1' => $itemFeature->category ?? null,
                    'feature2' => null,
                ];
            }
            
            // Calculate cosine similarity between feature vectors
            $similarity = $this->cosineSimilarity($featureVector, $storedVector);
            
            // Boost score if category matches
            $categoryBoost = ($itemFeature->category == $category) ? 0.15 : 0;
            
            // Boost score if color matches
            $colorBoost = ($itemFeature->color == $color) ? 0.1 : 0;
            
            // Combine scores (capped at 99% to avoid false 100% matches)
            $combinedScore = min(0.99, $similarity + $categoryBoost + $colorBoost);
            
            // Convert to percentage
            $matchPercentage = round($combinedScore * 100);
            
            return [
                'id' => $item->id,
                'name' => $item->title,
                'match_percentage' => $matchPercentage,
                'image_url' => $this->getItemImageUrl($item),
                'location' => $item->location ?? 'Unknown',
                'found_date' => $item->created_at->format('M j, Y'),
                'last_seen' => $this->formatLastSeen($item->updated_at),
                'color' => $itemFeature->color ?? 'Unknown',
                'feature1' => $itemFeature->category ?? null,
                'feature2' => null,
            ];
        })->sortByDesc('match_percentage');
    }
    
    /**
     * Calculate cosine similarity between two vectors
     *
     * @param array $vec1
     * @param array $vec2
     * @return float
     */
    private function cosineSimilarity($vec1, $vec2)
    {
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;
        
        for ($i = 0; $i < count($vec1); $i++) {
            $dotProduct += $vec1[$i] * $vec2[$i];
            $normA += $vec1[$i] * $vec1[$i];
            $normB += $vec2[$i] * $vec2[$i];
        }
        
        if ($normA == 0 || $normB == 0) {
            return 0;
        }
        
        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }
    
    /**
     * Get the image URL for an item
     *
     * @param \App\Models\Item $item
     * @return string
     */
    private function getItemImageUrl($item)
    {
        // Check if the item has an image property
        if ($item->image) {
            return $item->image; // Return the existing image URL
        }
        
        // Check for image_path property
        if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
            return url(Storage::url($item->image_path));
        }
        
        // Check if the item has related images
        if ($item->images && $item->images->count() > 0) {
            $primaryImage = $item->images->where('is_primary', true)->first() 
                            ?? $item->images->first();
                            
            if ($primaryImage && $primaryImage->path) {
                return url(Storage::url($primaryImage->path));
            }
        }
        
        // Check for a feature record with an image_path
        $feature = $item->features->first();
        if ($feature && $feature->image_path && Storage::disk('public')->exists('items/' . $feature->image_path)) {
            return url(Storage::url('items/' . $feature->image_path));
        }
        
        // Return placeholder as last resort
        return url('/images/placeholder-item.jpg');
    }
    
    /**
     * Format the last seen time in a user-friendly way
     *
     * @param \Carbon\Carbon $dateTime
     * @return string
     */
    private function formatLastSeen($dateTime)
    {
        $now = now();
        $diff = $dateTime->diffInDays($now);
        
        if ($diff == 0) {
            $hours = $dateTime->diffInHours($now);
            if ($hours == 0) {
                $mins = $dateTime->diffInMinutes($now);
                return ($mins <= 1) ? 'Just now' : "{$mins} mins ago";
            }
            return "{$hours} hours ago";
        }
        
        if ($diff == 1) {
            return 'Yesterday';
        }
        
        if ($diff < 7) {
            return "{$diff} days ago";
        }
        
        if ($diff < 30) {
            $weeks = ceil($diff / 7);
            return "{$weeks} " . ($weeks == 1 ? 'week' : 'weeks') . " ago";
        }
        
        return $dateTime->format('M j, Y');
    }

    /**
     * Calculate simple match scores based only on color and category
     * Used when TensorFlow features are not available
     *
     * @param \Illuminate\Database\Eloquent\Collection $itemFeatures
     * @param string $category
     * @param string $color
     * @return \Illuminate\Support\Collection
     */
    private function calculateSimpleMatchScores($itemFeatures, $category, $color)
    {
        return $itemFeatures->map(function ($itemFeature) use ($category, $color) {
            $item = $itemFeature->item;
            
            // Calculate a basic match score without feature vectors
            // Start at a lower base score to avoid matching unrelated items
            $baseScore = 30; // Lower base score (was 50)
            
            // Add score for category match
            $categoryBoost = ($itemFeature->category == $category) ? 40 : 0; // Increased importance
            
            // Add score for color match
            $colorBoost = ($itemFeature->color == $color) ? 30 : 0; // Increased importance
            
            // Additional boost for partial color match (e.g. "Dark Blue" matches "Blue")
            if ($colorBoost == 0 && 
                (stripos($itemFeature->color, $color) !== false || 
                 stripos($color, $itemFeature->color) !== false)) {
                $colorBoost = 15;
            }
            
            // Calculate combined score (capped at 99%)
            $matchPercentage = min(99, $baseScore + $categoryBoost + $colorBoost);
            
            return [
                'id' => $item->id,
                'name' => $item->title,
                'match_percentage' => $matchPercentage,
                'image_url' => $this->getItemImageUrl($item),
                'location' => $item->location ?? 'Unknown',
                'found_date' => $item->created_at->format('M j, Y'),
                'last_seen' => $this->formatLastSeen($item->updated_at),
                'color' => $itemFeature->color ?? 'Unknown',
                'feature1' => $itemFeature->category ?? null,
                'feature2' => null,
            ];
        })->sortByDesc('match_percentage');
    }

    /**
     * Guess category from color when no TensorFlow data is available
     *
     * @param string $color
     * @return string
     */
    private function guessCategoryFromColor($color)
    {
        // Very basic category guessing based on color
        // In a real system, you'd want something more sophisticated
        $colorCategoryMap = [
            'Black' => 'Electronics',
            'White' => 'Electronics',
            'Blue' => 'Clothing',
            'Red' => 'Clothing',
            'Green' => 'Clothing',
            'Brown' => 'Accessory',
            'Pink' => 'Accessory',
            'Purple' => 'Accessory',
            'Yellow' => 'Accessory',
            'Orange' => 'Accessory',
            'Silver' => 'Electronics',
            'Gold' => 'Accessory',
            'Gray' => 'Electronics',
            'Muted' => 'Clothing',
            'Multicolored' => 'Clothing'
        ];
        
        return $colorCategoryMap[$color] ?? 'Other';
    }

    /**
     * Save a lost item query for future matching
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveQuery(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:10240',
                'contact_email' => 'required|email',
                'item_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'features' => 'sometimes|json',
                'classifications' => 'sometimes|json'
            ]);

            // Store the lost item query
            $lostItem = new \App\Models\Item();
            $lostItem->title = $request->item_name;
            $lostItem->description = $request->description;
            $lostItem->status = 'active';  // Set status to active
            $lostItem->type = 'lost';      // Set type to lost
            $lostItem->contact_email = $request->contact_email;
            $lostItem->save();
            
            // Save the image
            $imagePath = $request->file('image')->store('items', 'public');
            $lostItem->images()->create([
                'path' => $imagePath
            ]);
            
            // Create the feature vector record
            $featureRecord = new \App\Models\ItemImageFeature();
            $featureRecord->item_id = $lostItem->id;
            
            // Extract color from the image
            $colorInfo = $this->analyzeImageColor($request->file('image'));
            $featureRecord->color = $colorInfo['color'];
            
            // Check if we have TensorFlow features or need to use backend-only mode
            $tensorflowMode = $request->has('features') && $request->has('classifications');
            
            if ($tensorflowMode) {
                // Parse the feature vector and classifications
                $featureVector = json_decode($request->features, true);
                $classifications = json_decode($request->classifications, true);
                
                // Store the feature vector as JSON
                $featureRecord->feature_vector = json_encode($featureVector);
                
                // Extract category from classifications
                $categoryInfo = $this->extractCategoryFromClassifications($classifications);
                $featureRecord->category = $categoryInfo['category'];
            } else {
                // Backend-only mode
                $featureRecord->feature_vector = null;
                $featureRecord->category = $this->guessCategoryFromColor($colorInfo['color']);
            }
            
            $featureRecord->save();
            
            // Run an immediate search for matches
            $matchResults = $this->findMatchesForLostItem($lostItem->id);
            
            return response()->json([
                'message' => 'Your lost item has been saved! We\'ll notify you if a matching item is found.',
                'item_id' => $lostItem->id,
                'current_matches' => $matchResults
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Error saving lost item query: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to save lost item query',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Find matches for a lost item
     *
     * @param int $lostItemId
     * @return array
     */
    private function findMatchesForLostItem($lostItemId)
    {
        // Get the lost item and its features
        $lostItem = \App\Models\Item::with('features')->findOrFail($lostItemId);
        $lostFeature = $lostItem->features->first();
        
        if (!$lostFeature) {
            return [];
        }
        
        // Get "found" items with feature vectors for comparison
        $foundItemFeatures = \App\Models\ItemImageFeature::with('item')
            ->whereHas('item', function($query) {
                $query->where('type', 'found')
                      ->where('status', 'active');
            })
            ->get();
            
        // If no found items, return empty results
        if ($foundItemFeatures->isEmpty()) {
            return [];
        }
        
        // Check if we have feature vectors for comparison
        if ($lostFeature->feature_vector) {
            $featureVector = json_decode($lostFeature->feature_vector, true);
            $matchResults = $this->calculateSimilarityScores(
                $featureVector, 
                $foundItemFeatures, 
                $lostFeature->category, 
                $lostFeature->color
            );
        } else {
            // Use simpler matching based on color and category
            $matchResults = $this->calculateSimpleMatchScores(
                $foundItemFeatures,
                $lostFeature->category,
                $lostFeature->color
            );
        }
        
        // Return only good matches
        return $matchResults
            ->filter(function ($item) {
                return $item['match_percentage'] >= 70; // Higher threshold for notifications
            })
            ->take(8)
            ->values()
            ->all();
    }

    /**
     * Debug endpoint to check the database for items
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function debug()
    {
        try {
            // Count total items
            $totalItems = Item::count();
            
            // Count found items by type
            $foundItems = Item::where('type', 'found')->count();
            
            // Count lost items by type
            $lostItems = Item::where('type', 'lost')->count();
            
            // Get feature vectors
            $featureCount = ItemImageFeature::count();
            
            // Get items with their features
            $items = Item::with('features')->limit(10)->get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'status' => $item->status,
                    'type' => $item->type,
                    'features' => $item->features->map(function($feature) {
                        return [
                            'id' => $feature->id,
                            'color' => $feature->color,
                            'category' => $feature->category,
                            'has_vector' => !empty($feature->feature_vector)
                        ];
                    })
                ];
            });
            
            return response()->json([
                'total_items' => $totalItems,
                'found_items' => $foundItems,
                'lost_items' => $lostItems,
                'feature_count' => $featureCount,
                'sample_items' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Debug failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 