<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemMatch;
use App\Notifications\NewMatchFound;
use App\Repositories\Contracts\MatcherInterface;
use App\Services\AttributeScorer;

class MatchingService implements MatcherInterface
{
    protected array $weights;
    protected float $threshold;
    protected int $timeframeWindowDays;
    protected float $locationRadiusKm;
    protected AttributeScorer $scorer;

    public function __construct(AttributeScorer $scorer)
    {
        $config = config('matching');
        $this->weights = $config['weights'];
        $this->threshold = $config['threshold'];
        $this->timeframeWindowDays = $config['timeframe_window_days'];
        $this->locationRadiusKm = $config['location_radius_km'];
        $this->scorer = $scorer;
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
                $match = ItemMatch::updateOrCreate(
                    ['lost_item_id' => $lostId, 'found_item_id' => $foundId],
                    ['score' => $score]
                );
                if ($match->wasRecentlyCreated) {
                    $match->lostItem->user->notify(new NewMatchFound($match));
                    $match->foundItem->user->notify(new NewMatchFound($match));
                }
            } else {
                ItemMatch::where('lost_item_id', $lostId)
                         ->where('found_item_id', $foundId)
                         ->delete();
            }
        }
    }

    protected function calculateScore(Item $a, Item $b): float
    {
        $rawCategory = $this->scorer->rawCategory($a, $b);
        $rawLocation = $this->scorer->rawLocation($a, $b);
        $rawTime     = $this->scorer->rawTimeframe($a, $b);
        $rawDesc     = $this->scorer->rawDescription($a, $b);

        return $rawCategory * $this->weights['category']
             + $rawLocation * $this->weights['location']
             + $rawTime     * $this->weights['timeframe']
             + $rawDesc     * $this->weights['description'];
    }

    public function scoreCategory(Item $a, Item $b): float
    {
        return $this->scorer->rawCategory($a, $b);
    }

    public function scoreLocation(Item $a, Item $b): float
    {
        return $this->scorer->rawLocation($a, $b);
    }

    public function scoreTimeframe(Item $a, Item $b): float
    {
        return $this->scorer->rawTimeframe($a, $b);
    }

    public function scoreDescription(Item $a, Item $b): float
    {
        return $this->scorer->rawDescription($a, $b);
    }
}
