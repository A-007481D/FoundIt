<?php

namespace App\Repositories;

use App\Repositories\Contracts\ItemRepositoryInterface;
use App\Models\Item;
use Illuminate\Support\Collection;

class EloquentItemRepository implements ItemRepositoryInterface
{
    public function getActiveVisibleByType(string $type): Collection
    {
        return Item::where('type', $type)
            ->where('status', 'active')
            ->where('visible', true)
            ->get();
    }

    public function find(int $id): ?Item
    {
        return Item::find($id);
    }

    public function getUserItemIds(int $userId): array
    {
        return Item::where('user_id', $userId)
            ->pluck('id')
            ->toArray();
    }
}
