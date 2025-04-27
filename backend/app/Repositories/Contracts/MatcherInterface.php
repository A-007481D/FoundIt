<?php

namespace App\Repositories\Contracts;

use App\Models\Item;

/**
 * Defines a contract for item matching implementations.
 */
interface MatcherInterface
{
    /**
     * Processes matching logic for the given item.
     *
     * @param Item $item
     */
    public function matchItem(Item $item): void;
}
