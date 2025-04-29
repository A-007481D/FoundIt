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
                'image' => 'required|image|max:10240',
                'features' => 'required|json',
                'classifications' => 'required|json'
            ]);

            // Parse the feature vector and classifications from the request
            $featureVector = json_decode($request->features, true);
            $classifications = json_decode($request->classifications, true);

            // Extract category from classifications
            $categoryInfo = $this->extractCategoryFromClassifications($classifications);
            $category = $categoryInfo['category'];
            
            // Extract color from the image
            $colorInfo = $this->analyzeImageColor($request->file('image'));
            
            // Get "found" items with feature vectors for comparison
            $itemFeatures = ItemImageFeature::whereNotNull('feature_vector')
                ->with('item')
                ->whereHas('item', function($query) {
                    $query->where('status', 'found');
                })
                ->get();

            // If we have no items with feature vectors, return empty results
            if ($itemFeatures->isEmpty()) {
                return response()->json([
                    'results' => [],
                    'category' => $category,
                    'color' => $colorInfo['color'],
                    'brand' => 'Unknown'
                ]);
            }

            // Calculate similarity scores
            $matchResults = $this->calculateSimilarityScores($featureVector, $itemFeatures, $category, $colorInfo['color']);

            // Return only the top matches that meet minimum threshold
            $results = $matchResults
                ->filter(function ($item) {
                    return $item['match_percentage'] >= 50; // Minimum 50% match
                })
                ->take(8) // Limit to 8 results
                ->values()
                ->all();

            return response()->json([
                'results' => $results,
                'category' => $category,
                'color' => $colorInfo['color'],
                'brand' => $this->guessBrand($classifications, $category)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in item detective search: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to process image',
                'message' => $e->getMessage()
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
            Log::error('Error analyzing image color: ' . $e->getMessage());
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
                    'name' => $item->name,
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
                'name' => $item->name,
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
        if ($item->image_path) {
            return url(Storage::url($item->image_path));
        }
        
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
} 