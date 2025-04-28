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
        return $a->category_id === $b->category_id ? 1.0 : 0.0;
    }

    /**
     * Raw description similarity (0.0–1.0)
     */
    public function rawDescription(Item $a, Item $b): float
    {
        $percent = 0;
        similar_text(strtolower($a->description), strtolower($b->description), $percent);
        return $percent / 100.0;
    }

    /**
     * Raw timeframe proximity (0.0–1.0)
     */
    public function rawTimeframe(Item $a, Item $b): float
    {
        $dateA = $a->type === 'lost' ? $a->lost_date : $a->found_date;
        $dateB = $b->type === 'found' ? $b->found_date : $b->lost_date;
        if (! $dateA || ! $dateB) {
            return 0.0;
        }
        $diff = abs($dateA->diffInDays($dateB));
        if ($diff > $this->timeframeWindowDays) {
            return 0.0;
        }
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
            return 0.0;
        }
        [$latA, $lngA] = array_map('floatval', $partsA);
        [$latB, $lngB] = array_map('floatval', $partsB);
        $distance = $this->haversine($latA, $lngA, $latB, $lngB);
        if ($distance > $this->locationRadiusKm) {
            return 0.0;
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
