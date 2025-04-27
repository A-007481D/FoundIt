<?php

namespace App\Repositories\Contracts;

use App\Models\ItemMatch;
use Illuminate\Support\Collection;

interface ItemMatchRepositoryInterface
{
    /**
     * Retrieve matches for given user item IDs.
     *
     * @param int[] $itemIds
     * @return Collection<ItemMatch>
     */
    public function getForUserItems(array $itemIds): Collection;

    /**
     * Create or update a match record.
     *
     * @param int $lostId
     * @param int $foundId
     * @param float $score
     * @return ItemMatch
     */
    public function createOrUpdateMatch(int $lostId, int $foundId, float $score): ItemMatch;

    /**
     * Delete a match record.
     *
     * @param int $lostId
     * @param int $foundId
     * @return void
     */
    public function deleteMatch(int $lostId, int $foundId): void;
}
