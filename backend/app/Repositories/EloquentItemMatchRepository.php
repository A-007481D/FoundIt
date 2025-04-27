<?php

namespace App\Repositories;

use App\Models\ItemMatch;
use App\Repositories\Contracts\ItemMatchRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentItemMatchRepository implements ItemMatchRepositoryInterface
{
    public function getForUserItems(array $itemIds): Collection
    {
        return ItemMatch::with(['lostItem.user', 'foundItem.user', 'lostItem.category', 'foundItem.category'])
            ->where(function ($q) use ($itemIds) {
                $q->whereIn('lost_item_id', $itemIds)
                  ->orWhereIn('found_item_id', $itemIds);
            })
            ->get()
            ->filter(fn($m) => $m->lostItem && $m->foundItem);
    }

    public function createOrUpdateMatch(int $lostId, int $foundId, float $score): ItemMatch
    {
        return ItemMatch::updateOrCreate(
            ['lost_item_id' => $lostId, 'found_item_id' => $foundId],
            ['score' => $score]
        );
    }

    public function deleteMatch(int $lostId, int $foundId): void
    {
        ItemMatch::where('lost_item_id', $lostId)
            ->where('found_item_id', $foundId)
            ->delete();
    }
}
