<?php

namespace App\Observers;

use App\Models\Item;
use App\Services\MatchService;
use Illuminate\Support\Facades\Log;

class ItemObserver
{
    protected $matchService;

    public function __construct(MatchService $matchService)
    {
        $this->matchService = $matchService;
    }

    /**
     * Handle the Item "created" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function created(Item $item)
    {
        $this->processMatching($item, 'created');
    }

    /**
     * Handle the Item "updated" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function updated(Item $item)
    {
        $relevantFields = ['title', 'description', 'location', 'category_id', 'lost_date', 'found_date', 'visible', 'status'];
        $hasRelevantChanges = false;
        
        foreach ($relevantFields as $field) {
            if ($item->isDirty($field)) {
                $hasRelevantChanges = true;
                break;
            }
        }
        
        if ($hasRelevantChanges) {
            $this->processMatching($item, 'updated');
        }
    }
    
    /**
     * Process matching for an item
     * 
     * @param Item $item
     * @param string $event
     * @return void
     */
    private function processMatching(Item $item, string $event)
    {
        if ($item->status !== 'active' || !$item->visible) {
            Log::info("Skipping match processing for item {$item->id}: not active or not visible");
            return;
        }
        
        try {
            Log::info("Processing matching for item {$item->id} ({$event})");
            $this->matchService->process($item);
        } catch (\Exception $e) {
            Log::error("Error processing matches for item {$item->id}: " . $e->getMessage());
        }
    }
}
