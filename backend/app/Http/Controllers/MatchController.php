<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\ItemMatch;
use App\Services\MatchingService;

class MatchController extends Controller
{
    /**
     * Return potential matches for the authenticated user's items.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $itemIds = Item::where('user_id', $userId)->pluck('id')->toArray();

        $matches = ItemMatch::with(['lostItem.user', 'foundItem.user', 'lostItem.category', 'foundItem.category'])
            ->whereIn('lost_item_id', $itemIds)
            ->orWhereIn('found_item_id', $itemIds)
            ->get();

        $service = app(MatchingService::class);

        $formatted = $matches->map(function($m) use ($service) {
            $lost = $m->lostItem;
            $found = $m->foundItem;
            $attrs = [];
            if ($service->scoreCategory($lost, $found) > 0) {
                $attrs[] = 'Catégorie exacte';
            }
            if ($service->scoreLocation($lost, $found) > 0) {
                $attrs[] = 'Proximité géographique';
            }
            if ($service->scoreTimeframe($lost, $found) > 0) {
                $attrs[] = 'Période';
            }
            if ($service->scoreDescription($lost, $found) > 0) {
                $attrs[] = 'Description';
            }

            return [
                'id' => $m->id,
                'matchScore' => (int) round($m->score * 100),
                'status' => 'new',
                'lostItem' => [
                    'id' => $lost->id,
                    'title' => $lost->title,
                    'description' => $lost->description,
                    'location' => $lost->location,
                    'date' => $lost->lost_date->toDateString(),
                    'time' => $lost->lost_date->format('H:i'),
                    'category' => $lost->category->name ?? null,
                    'user' => [
                        'name' => $lost->user->firstname . ' ' . $lost->user->lastname,
                        'avatar' => $lost->user->avatar ?? null,
                        'initials' => strtoupper(substr($lost->user->firstname, 0, 1) . substr($lost->user->lastname, 0, 1)),
                    ],
                ],
                'foundItem' => [
                    'id' => $found->id,
                    'title' => $found->title,
                    'description' => $found->description,
                    'location' => $found->location,
                    'date' => $found->found_date->toDateString(),
                    'time' => $found->found_date->format('H:i'),
                    'category' => $found->category->name ?? null,
                    'user' => [
                        'name' => $found->user->firstname . ' ' . $found->user->lastname,
                        'avatar' => $found->user->avatar ?? null,
                        'initials' => strtoupper(substr($found->user->firstname, 0, 1) . substr($found->user->lastname, 0, 1)),
                    ],
                ],
                'matchingAttributes' => $attrs,
            ];
        });

        return response()->json($formatted);
    }
}
