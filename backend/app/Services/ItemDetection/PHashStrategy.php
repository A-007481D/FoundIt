<?php

namespace App\Services\ItemDetection;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use App\Models\ItemImageFeature;
use Illuminate\Support\Facades\Storage;
use Jenssegers\ImageHash\ImageHash;

class PHashStrategy implements DetectiveStrategyInterface
{
    /**
     * Search using perceptual hash (pHash) matching
     */
    public function search(UploadedFile $image, array $options = []): Collection
    {
        $hasher = new ImageHash();
        $queryHash = $hasher->hash($image->getRealPath());
        $queryHex = $queryHash->toHex();
        // Convert hex to bit string
        $queryBits = '';
        foreach (str_split($queryHex) as $hex) {
            $queryBits .= str_pad(base_convert($hex, 16, 2), 4, '0', STR_PAD_LEFT);
        }

        $items = ItemImageFeature::with('item')
            ->whereHas('item', fn($q) => $q->where('type','found')->where('status','active'))
            ->get();

        $results = collect();
        foreach ($items as $itemFeature) {
            if (!$itemFeature->phash) continue;
            $bits2 = '';
            foreach (str_split($itemFeature->phash) as $hex) {
                $bits2 .= str_pad(base_convert($hex, 16, 2), 4, '0', STR_PAD_LEFT);
            }
            $len = min(strlen($queryBits), strlen($bits2));
            $distance = 0;
            for ($i = 0; $i < $len; $i++) {
                if ($queryBits[$i] !== $bits2[$i]) {
                    $distance++;
                }
            }
            $matchPercentage = round((1 - ($distance / $len)) * 100);
            // Build image URL
            $url = url('/images/placeholder-item.jpg');
            if ($itemFeature->image_path && Storage::disk('public')->exists($itemFeature->image_path)) {
                $url = url(Storage::url($itemFeature->image_path));
            }
            $results->push([
                'id' => $itemFeature->item->id,
                'name' => $itemFeature->item->title,
                'match_percentage' => $matchPercentage,
                'image_url' => $url,
                'location' => $itemFeature->item->location ?? 'Unknown',
                'found_date' => $itemFeature->item->created_at->format('M j, Y'),
                'last_seen' => $itemFeature->item->updated_at->diffForHumans(),
                'color' => $itemFeature->color ?? 'Unknown',
                'feature1' => $itemFeature->category ?? null,
                'feature2' => null,
            ]);
        }

        return $results->sortByDesc('match_percentage')->take(8);
    }
}
