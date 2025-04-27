<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Collection;
use App\Repositories\Contracts\ItemRepositoryInterface;
use App\Repositories\Contracts\ItemMatchRepositoryInterface;
use App\Repositories\Contracts\MatcherInterface;

/**
 * Orchestrates matching operations and formats results for the application.
 */
class MatchService
{
    protected ItemRepositoryInterface $itemRepo;
    protected ItemMatchRepositoryInterface $matchRepo;
    protected MatcherInterface $matcher;

    public function __construct(
        ItemRepositoryInterface $itemRepo,
        ItemMatchRepositoryInterface $matchRepo,
        MatcherInterface $matcher
    ) {
        $this->itemRepo = $itemRepo;
        $this->matchRepo = $matchRepo;
        $this->matcher  = $matcher;
    }

    /**
     * Process matching logic for a given item.
     */
    public function process(Item $item): void
    {
        $this->matcher->matchItem($item);
    }

    /**
     * Retrieve and format matches for a specific user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getForUser(int $userId): Collection
    {
        $itemIds = $this->itemRepo->getUserItemIds($userId);
        $matches = $this->matchRepo->getForUserItems($itemIds);

        return $matches->map(fn($m) => [
            'id'               => $m->id,
            'matchScore'       => (int) round($m->score * 100),
            'status'           => 'new',
            'lostItem'         => [
                'id'          => $m->lostItem->id,
                'title'       => $m->lostItem->title,
                'description' => $m->lostItem->description,
                'location'    => $m->lostItem->location,
                'date'        => optional($m->lostItem->lost_date)->toDateString(),
                'time'        => optional($m->lostItem->lost_date)->format('H:i'),
                'category'    => optional($m->lostItem->category)->name,
                'user'        => [
                    'name'     => optional($m->lostItem->user)->firstname . ' ' . optional($m->lostItem->user)->lastname,
                    'avatar'   => optional($m->lostItem->user)->avatar,
                    'initials' => strtoupper(substr(optional($m->lostItem->user)->firstname ?? '', 0, 1) . substr(optional($m->lostItem->user)->lastname ?? '', 0, 1)),
                ],
            ],
            'foundItem'        => [
                'id'          => $m->foundItem->id,
                'title'       => $m->foundItem->title,
                'description' => $m->foundItem->description,
                'location'    => $m->foundItem->location,
                'date'        => optional($m->foundItem->found_date)->toDateString(),
                'time'        => optional($m->foundItem->found_date)->format('H:i'),
                'category'    => optional($m->foundItem->category)->name,
                'user'        => [
                    'name'     => optional($m->foundItem->user)->firstname . ' ' . optional($m->foundItem->user)->lastname,
                    'avatar'   => optional($m->foundItem->user)->avatar,
                    'initials' => strtoupper(substr(optional($m->foundItem->user)->firstname ?? '', 0, 1) . substr(optional($m->foundItem->user)->lastname ?? '', 0, 1)),
                ],
            ],
            'matchingAttributes' => method_exists($this->matcher, 'scoreCategory')
                ? $this->gatherAttributes($m)
                : [],
        ]);
    }

    /**
     * Gather detailed matching attributes when using legacy matcher.
     */
    protected function gatherAttributes($match): array
    {
        $attributes = [];
        $lost  = $match->lostItem;
        $found = $match->foundItem;

        if ($this->matcher->scoreCategory($lost, $found) > 0) {
            $attributes[] = 'Exact category';
        }
        if ($this->matcher->scoreLocation($lost, $found) > 0) {
            $attributes[] = 'Geographic proximity';
        }
        if ($this->matcher->scoreTimeframe($lost, $found) > 0) {
            $attributes[] = 'Timeframe';
        }
        if ($this->matcher->scoreDescription($lost, $found) > 0) {
            $attributes[] = 'Description';
        }

        return $attributes;
    }
}
