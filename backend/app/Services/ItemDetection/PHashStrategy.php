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
        // Exact-match via perceptual hash (only identical images)
        $hasher = new ImageHash();
        $queryHex = $hasher->hash($image->getRealPath())->toHex();
        $features = ItemImageFeature::with('item')
            ->where('phash', $queryHex)
            ->whereHas('item', fn($q) => $q->where('type', 'found')->where('status', 'active'))
            ->get();
        $results = collect();
        foreach ($features as $f) {
            $url = url('/images/placeholder-item.jpg');
            if ($f->image_path && Storage::disk('public')->exists($f->image_path)) {
                $url = url(Storage::url($f->image_path));
            }
            $results->push([
                'id'              => $f->item->id,
                'name'            => $f->item->title,
                'match_percentage'=> 100,
                'image_url'       => $url,
                'location'        => $f->item->location ?? 'Unknown',
                'found_date'      => $f->item->created_at->format('M j, Y'),
                'last_seen'       => $f->item->updated_at->diffForHumans(),
                'color'           => $f->color ?? 'Unknown',
                'feature1'        => $f->category ?? null,
                'feature2'        => null,
            ]);
        }
        return $results;
    }
}
