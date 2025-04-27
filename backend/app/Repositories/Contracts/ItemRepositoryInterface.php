<?php

namespace App\Repositories\Contracts;

use App\Models\Item;
use Illuminate\Support\Collection;

interface ItemRepositoryInterface
{
    /**
     * Get all active, visible items of a given type.
     *
     * @param string $type
     * @return Collection<Item>
     */
    public function getActiveVisibleByType(string $type): Collection;

    /**
     * Find an item by its ID.
     *
     * @param int $id
     * @return Item|null
     */
    public function find(int $id): ?Item;

    /**
     * Get all item IDs for a given user.
     *
     * @param int $userId
     * @return int[]
     */
    public function getUserItemIds(int $userId): array;
}
