<?php

namespace App\Services;

use App\Models\Item;

class AttributeScorer
{
    protected array $weights;
    protected int $timeframeWindowDays;
    protected float $locationRadiusKm;

    public function __construct()
    {
        $config = config('matching');
        $this->weights = $config['weights'];
        $this->timeframeWindowDays = $config['timeframe_window_days'];
        $this->locationRadiusKm = $config['location_radius_km'];
    }

    /**
     * Raw category match: 1.0 if same category, else 0.0
     */
    public function rawCategory(Item $a, Item $b): float
    {
        // If either item has no category_id, but descriptions are similar, give partial match
        if ($a->category_id === null || $b->category_id === null) {
            $descSimilarity = 0;
            similar_text(strtolower($a->description), strtolower($b->description), $descSimilarity);
            if ($descSimilarity > 50) {
                return 0.5; // Partial match based on description
            }
            return 0.0;
        }
        
        return $a->category_id === $b->category_id ? 1.0 : 0.0;
    }

    /**
     * Raw description similarity (0.0–1.0)
     */
    public function rawDescription(Item $a, Item $b): float
    {
        if (empty($a->description) || empty($b->description)) {
            // If either description is empty but titles are similar, give partial score
            $titleSimilarity = 0;
            if (!empty($a->title) && !empty($b->title)) {
                similar_text(strtolower($a->title), strtolower($b->title), $titleSimilarity);
                return $titleSimilarity / 100.0;
            }
            return 0.1; // Minimum match score
        }

        $percent = 0;
        similar_text(strtolower($a->description), strtolower($b->description), $percent);
        
        // Even low similarity should count somewhat
        if ($percent < 30) {
            return max(0.1, $percent / 100.0);
        }
        
        return $percent / 100.0;
    }

    /**
     * Raw timeframe proximity (0.0–1.0)
     */
    public function rawTimeframe(Item $a, Item $b): float
    {
        $lostDate = $a->type === 'lost' ? $a->lost_date : $b->lost_date;
        $foundDate = $a->type === 'found' ? $a->found_date : $b->found_date;
        
        if (!$lostDate || !$foundDate) {
            return 0.3; // Default score if dates aren't comparable
        }
        
        // Found date should typically be after lost date
        if ($foundDate->lt($lostDate)) {
            $diff = abs($lostDate->diffInDays($foundDate));
            // If found before lost, only match if within a few days (might be reporting delay)
            if ($diff <= 3) {
                return 0.5;
            }
            return 0.1;
        }
        
        $diff = abs($lostDate->diffInDays($foundDate));
        if ($diff > $this->timeframeWindowDays) {
            return 0.1; // Minimal score for items outside window
        }
        
        // Higher score for closer dates
        return 1.0 - ($diff / $this->timeframeWindowDays);
    }

    /**
     * Raw geographic proximity (0.0–1.0)
     */
    public function rawLocation(Item $a, Item $b): float
    {
        $partsA = explode(',', $a->location);
        $partsB = explode(',', $b->location);
        
        if (count($partsA) < 2 || count($partsB) < 2) {
            // String matching if coordinates aren't available
            $similarity = 0;
            similar_text($a->location, $b->location, $similarity);
            return max(0.2, $similarity / 100.0);
        }
        
        [$latA, $lngA] = array_map('floatval', $partsA);
        [$latB, $lngB] = array_map('floatval', $partsB);
        
        // Handle invalid coordinates
        if ($latA == 0 && $lngA == 0 || $latB == 0 && $lngB == 0) {
            return 0.2; // Default score for invalid coordinates
        }
        
        $distance = $this->haversine($latA, $lngA, $latB, $lngB);
        if ($distance > $this->locationRadiusKm) {
            // Give some minimal score even for distant items
            return 0.1;
        }
        
        return 1.0 - ($distance / $this->locationRadiusKm);
    }

    /**
     * Haversine formula for distance in km
     */
    protected function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earth = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earth * $c;
    }
}
