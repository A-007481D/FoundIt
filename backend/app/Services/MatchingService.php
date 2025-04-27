<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemMatch;

class MatchingService
{
    protected array $weights;
    protected float $threshold;
    protected int $timeframeWindowDays;
    protected float $locationRadiusKm;

    public function __construct()
    {
        $config = config('matching');
        $this->weights = $config['weights'];
        $this->threshold = $config['threshold'];
        $this->timeframeWindowDays = $config['timeframe_window_days'];
        $this->locationRadiusKm = $config['location_radius_km'];
    }

    public function matchItem(Item $item): void
    {
        $otherType = $item->type === 'lost' ? 'found' : 'lost';

        $candidates = Item::where('type', $otherType)
                          ->where('status', 'active')
                          ->where('visible', true)
                          ->get();

        foreach ($candidates as $candidate) {
            $lostId = $item->type === 'lost' ? $item->id : $candidate->id;
            $foundId = $item->type === 'found' ? $item->id : $candidate->id;

            $score = $this->calculateScore($item, $candidate);

            if ($score >= $this->threshold) {
                ItemMatch::updateOrCreate(
                    ['lost_item_id' => $lostId, 'found_item_id' => $foundId],
                    ['score' => $score]
                );
            } else {
                ItemMatch::where('lost_item_id', $lostId)
                         ->where('found_item_id', $foundId)
                         ->delete();
            }
        }
    }

    protected function calculateScore(Item $a, Item $b): float
    {
        $categoryScore = $this->scoreCategory($a, $b);
        $locationScore = $this->scoreLocation($a, $b);
        $timeScore = $this->scoreTimeframe($a, $b);
        $descScore = $this->scoreDescription($a, $b);

        return $categoryScore * $this->weights['category']
             + $locationScore * $this->weights['location']
             + $timeScore * $this->weights['timeframe']
             + $descScore * $this->weights['description'];
    }

    public function scoreCategory(Item $a, Item $b): float
    {
        return $a->category_id === $b->category_id ? 1.0 : 0.0;
    }

    public function scoreLocation(Item $a, Item $b): float
    {
        try {
            [$lat1, $lon1] = explode(',', $a->location);
            [$lat2, $lon2] = explode(',', $b->location);
            $earthRadius = 6371; // km
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);
            $lat1 = deg2rad($lat1);
            $lat2 = deg2rad($lat2);

            $h = sin($dLat / 2) * sin($dLat / 2)
                 + sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
            $distance = 2 * $earthRadius * asin(min(1, sqrt($h)));

            if ($distance > $this->locationRadiusKm) {
                return 0.0;
            }
            return 1.0 - ($distance / $this->locationRadiusKm);
        } catch (\Exception $e) {
            return 0.0;
        }
    }

    public function scoreTimeframe(Item $a, Item $b): float
    {
        $dateA = $a->type === 'lost' ? $a->lost_date : $a->found_date;
        $dateB = $b->type === 'found' ? $b->found_date : $b->lost_date;

        if (!$dateA || !$dateB) {
            return 0.0;
        }
        $diff = abs($dateA->diffInDays($dateB));
        if ($diff > $this->timeframeWindowDays) {
            return 0.0;
        }
        return 1.0 - ($diff / $this->timeframeWindowDays);
    }

    public function scoreDescription(Item $a, Item $b): float
    {
        $descA = strtolower($a->description);
        $descB = strtolower($b->description);
        similar_text($descA, $descB, $percent);
        return $percent / 100.0;
    }
}
