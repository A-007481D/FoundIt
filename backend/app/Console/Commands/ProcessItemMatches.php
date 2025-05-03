<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Services\MatchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessItemMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:process-matches {--id= : Process a specific item ID} {--type= : Process only items of specified type (lost/found)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process matching for all active items or a specific item';

    /**
     * Execute the console command.
     */
    public function handle(MatchService $matchService)
    {
        $itemId = $this->option('id');
        $type = $this->option('type');

        $query = Item::query()
            ->where('status', 'active')
            ->where('visible', true);

        if ($itemId) {
            $query->where('id', $itemId);
        }

        if ($type) {
            $query->where('type', $type);
        }

        $items = $query->get();
        $count = $items->count();

        if ($count === 0) {
            $this->error('No matching items found.');
            return 1;
        }

        $this->info("Processing matches for {$count} items...");
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $processedCount = 0;
        $errorCount = 0;

        foreach ($items as $item) {
            try {
                $matchService->process($item);
                $processedCount++;
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Error processing matches for item {$item->id}: " . $e->getMessage());
                $this->newLine();
                $this->error("Error with item {$item->id}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Matching process completed: {$processedCount} items processed, {$errorCount} errors.");

        $this->showMatchSummary();

        return 0;
    }

    /**
     * Show a summary of current matches in the system
     */
    private function showMatchSummary()
    {
        $this->newLine();
        $this->info('Current Match Summary:');
        
        $matches = \App\Models\ItemMatch::with(['lostItem', 'foundItem'])->get();
        
        if ($matches->isEmpty()) {
            $this->warn('No matches found in the database.');
            return;
        }
        
        $this->info("Total matches: " . $matches->count());
        
        $this->table(
            ['ID', 'Lost Item', 'Found Item', 'Score %'],
            $matches->map(function ($match) {
                return [
                    $match->id,
                    $match->lostItem ? "{$match->lostItem->id}: {$match->lostItem->title}" : 'N/A',
                    $match->foundItem ? "{$match->foundItem->id}: {$match->foundItem->title}" : 'N/A',
                    round($match->score * 100)
                ];
            })
        );
    }
}
