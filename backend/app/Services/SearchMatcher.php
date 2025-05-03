<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemMatch;
use App\Notifications\NewMatchFound;
use App\Repositories\Contracts\MatcherInterface;
use App\Services\AttributeScorer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/**
 * Advanced matcher using Meilisearch/Typesense via Laravel Scout.
 */
class SearchMatcher implements MatcherInterface
{
    protected AttributeScorer $scorer;

    public function __construct(AttributeScorer $scorer)
    {
        $this->scorer = $scorer;
    }

    public function matchItem(Item $item): void
    {
        Log::info("SearchMatcher.matchItem start: item={$item->id}, type={$item->type}");
        $otherType = $item->type === 'lost' ? 'found' : 'lost';
        $weights = Config::get('matching.weights');
        $threshold = Config::get('matching.threshold', 0.5);

        $candidates = $this->getCandidateItemsViaSearch($item, $otherType);
        
        if ($candidates->isEmpty()) {
            Log::info("Search returned no results for item {$item->id}, falling back to direct query");
            $candidates = $this->getCandidateItemsViaDirectQuery($item, $otherType);
        }
        
        Log::info(sprintf('SearchMatcher.matchItem: item %d yielded %d candidates', 
            $item->id, count($candidates)));

        foreach ($candidates as $otherItem) {
            // compute weighted attribute scores via AttributeScorer
            $scoreCategory    = $this->scorer->rawCategory($item, $otherItem) * $weights['category'];
            $scoreDescription = $this->scorer->rawDescription($item, $otherItem) * $weights['description'];
            $scoreTimeframe   = $this->scorer->rawTimeframe($item, $otherItem) * $weights['timeframe'];
            $scoreLocation    = $this->scorer->rawLocation($item, $otherItem) * $weights['location'];
            $score = $scoreCategory + $scoreDescription + $scoreTimeframe + $scoreLocation;
            
            Log::info(sprintf(
                'SearchMatcher.matchItem scoring: item=%d, otherItem=%d, cat=%.2f desc=%.2f time=%.2f loc=%.2f total=%.2f',
                $item->id, $otherItem->id, $scoreCategory, $scoreDescription, $scoreTimeframe, $scoreLocation, $score
            ));
            
            $lostId  = $item->type === 'lost'  ? $item->id : $otherItem->id;
            $foundId = $item->type === 'found' ? $item->id : $otherItem->id;
            
            if ($score >= $threshold) {
                $match = ItemMatch::updateOrCreate(
                    ['lost_item_id' => $lostId, 'found_item_id' => $foundId],
                    ['score' => $score]
                );
                
                Log::info(sprintf(
                    'Match created/updated: lost_item_id=%d, found_item_id=%d, score=%.2f, new=%s',
                    $lostId, $foundId, $score, $match->wasRecentlyCreated ? 'true' : 'false'
                ));
                
                if ($match->wasRecentlyCreated) {
                    // Send notifications only for newly created matches
                    if ($match->lostItem && $match->lostItem->user) {
                        $match->lostItem->user->notify(new NewMatchFound($match));
                    }
                    if ($match->foundItem && $match->foundItem->user) {
                        $match->foundItem->user->notify(new NewMatchFound($match));
                    }
                }
            } else {
                // Delete any existing match that fell below threshold
                $deleted = ItemMatch::where('lost_item_id', $lostId)
                    ->where('found_item_id', $foundId)
                    ->delete();
                
                if ($deleted) {
                    Log::info("Deleted match: lost_item_id={$lostId}, found_item_id={$foundId} (score too low: {$score})");
                }
            }
        }
    }
    
    /**
     * Get candidate items via Laravel Scout search
     */
    private function getCandidateItemsViaSearch(Item $item, string $otherType)
    {
        try {
            $query = '';
            if (!empty($item->title)) {
                $query .= $item->title . ' ';
            }
            if (!empty($item->description)) {
                $query .= $item->description;
            }
            
            return Item::search($query)
                ->where('type', $otherType)
                ->where('status', 'active')
                ->where('visible', true)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error in SearchMatcher search query: ' . $e->getMessage());
            return collect();
        }
    }
    
    /**
     * Get candidate items via direct database query as fallback
     */
    private function getCandidateItemsViaDirectQuery(Item $item, string $otherType)
    {
        try {
            $query = Item::where('type', $otherType)
                ->where('status', 'active')
                ->where('visible', true);
            
            // Add title-based matching if title exists
            if (!empty($item->title)) {
                $query->where(function($q) use ($item) {
                    $q->where('title', 'like', '%' . $item->title . '%')
                      ->orWhere('title', 'like', '%' . str_replace(' ', '%', $item->title) . '%');
                });
            }
            
            if (!empty($item->category_id)) {
                $query->orWhere('category_id', $item->category_id);
            }
            
            return $query->get();
        } catch (\Exception $e) {
            Log::error('Error in SearchMatcher direct query: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Category match: binary
     */
    public function scoreCategory(Item $a, Item $b): float
    {
        return $a->category_id === $b->category_id
            ? Config::get('matching.weights.category', 0)
            : 0;
    }

    /**
     * Description similarity via similar_text
     */
    public function scoreDescription(Item $a, Item $b): float
    {
        $percent = 0;
        similar_text(strtolower($a->description), strtolower($b->description), $percent);
        return ($percent / 100) * Config::get('matching.weights.description', 0);
    }

    /**
     * Timeframe scoring: closeness in days
     */
    public function scoreTimeframe(Item $a, Item $b): float
    {
        $dateFieldA = $a->type . '_date';
        $dateFieldB = $b->type . '_date';
        $dateA = $a->{$dateFieldA};
        $dateB = $b->{$dateFieldB};
        if (! $dateA || ! $dateB) {
            return 0;
        }
        $diff = abs($dateA->diffInDays($dateB));
        $window = Config::get('matching.timeframe_window_days', 30);
        if ($diff > $window) {
            return 0;
        }
        return (1 - ($diff / $window)) * Config::get('matching.weights.timeframe', 0);
    }

    /**
     * Geographic proximity using haversine
     */
    public function scoreLocation(Item $a, Item $b): float
    {
        $partsA = explode(',', $a->location);
        $partsB = explode(',', $b->location);
        if (count($partsA) < 2 || count($partsB) < 2) {
            return 0;
        }
        [$latA, $lngA] = array_map('floatval', $partsA);
        [$latB, $lngB] = array_map('floatval', $partsB);
        $radius = Config::get('matching.location_radius_km', 0);
        $distance = $this->haversine($latA, $lngA, $latB, $lngB);
        if ($distance > $radius) {
            return 0;
        }
        return (1 - ($distance / $radius)) * Config::get('matching.weights.location', 0);
    }

    /**
     * Haversine formula for distance in km
     */
    protected function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earth = 6371; // km radius
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earth * $c;
    }

    protected function getLatLng(Item $item): string
    {
        $parts = explode(',', $item->location);
        if (count($parts) < 2) {
            return '0,0';
        }
        [$lat, $lng] = $parts;
        return trim($lat) . ',' . trim($lng);
    }
}
