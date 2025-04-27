<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\ItemMatch;
use App\Services\MatchingService;
use Illuminate\Support\Facades\Log;

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
            ->where(function($q) use ($itemIds) {
                $q->whereIn('lost_item_id', $itemIds)
                  ->orWhereIn('found_item_id', $itemIds);
            })
            ->get()
            ->filter(fn($m) => $m->lostItem && $m->foundItem);

        $service = app(MatchingService::class);

        try {
            $formatted = $matches->map(function($m) use ($service) {
                $lost = $m->lostItem;
                $found = $m->foundItem;
                $attrs = [];
                if ($service->scoreCategory($lost, $found) > 0) {
                    $attrs[] = 'Exact category';
                }
                if ($service->scoreLocation($lost, $found) > 0) {
                    $attrs[] = 'Geographic proximity';
                }
                if ($service->scoreTimeframe($lost, $found) > 0) {
                    $attrs[] = 'Timeframe';
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
                        'date' => optional($lost->lost_date)->toDateString(),
                        'time' => optional($lost->lost_date)->format('H:i'),
                        'category' => optional($lost->category)->name,
                        'user' => [
                            'name' => optional($lost->user)->firstname . ' ' . optional($lost->user)->lastname,
                            'avatar' => optional($lost->user)->avatar,
                            'initials' => strtoupper(substr(optional($lost->user)->firstname ?? '', 0, 1) . substr(optional($lost->user)->lastname ?? '', 0, 1)),
                        ],
                    ],
                    'foundItem' => [
                        'id' => $found->id,
                        'title' => $found->title,
                        'description' => $found->description,
                        'location' => $found->location,
                        'date' => optional($found->found_date)->toDateString(),
                        'time' => optional($found->found_date)->format('H:i'),
                        'category' => optional($found->category)->name,
                        'user' => [
                            'name' => optional($found->user)->firstname . ' ' . optional($found->user)->lastname,
                            'avatar' => optional($found->user)->avatar,
                            'initials' => strtoupper(substr(optional($found->user)->firstname ?? '', 0, 1) . substr(optional($found->user)->lastname ?? '', 0, 1)),
                        ],
                    ],
                    'matchingAttributes' => $attrs,
                ];
            });
            return response()->json($formatted);
        } catch (\Throwable $e) {
            Log::error('MatchController@index error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Server error retrieving matches'], 500);
        }
    }
}
