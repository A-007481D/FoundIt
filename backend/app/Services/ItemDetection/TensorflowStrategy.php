<?php

namespace App\Services\ItemDetection;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use App\Models\ItemImageFeature;
use Illuminate\Support\Facades\Storage;

class TensorflowStrategy implements DetectiveStrategyInterface
{
    /**
     * Search using TensorFlow feature vectors
     */
    public function search(UploadedFile $image, array $options = []): Collection
    {
        $featureVector = $options['features'] ?? [];
        $classifications = $options['classifications'] ?? [];

        // Determine category from classifications
        $category = 'Unknown';
        if (!empty($classifications) && isset($classifications[0]['className'])) {
            $category = explode(',', $classifications[0]['className'])[0];
        }

        $itemFeatures = ItemImageFeature::with('item')
            ->whereHas('item', fn($q) => $q->where('type','found')->where('status','active'))
            ->get();

        $results = collect();

        foreach ($itemFeatures as $f) {
            $item = $f->item;
            $stored = $f->feature_vector ?? [];
            if (!is_array($stored) || count($stored) !== count($featureVector)) {
                $matchPct = 0;
            } else {
                // Cosine similarity
                $dot = 0; $normA = 0; $normB = 0;
                foreach ($featureVector as $i => $v) {
                    $dot += $v * ($stored[$i] ?? 0);
                    $normA += $v * $v;
                    $normB += ($stored[$i] ?? 0) * ($stored[$i] ?? 0);
                }
                $similarity = ($normA > 0 && $normB > 0) ? $dot / (sqrt($normA) * sqrt($normB)) : 0;
                $boostCat = ($f->category === $category) ? 0.15 : 0;
                // color boost optional
                $boostColor = ($f->color === ($options['color'] ?? $f->color)) ? 0.1 : 0;
                $matchPct = round(min(0.99, $similarity + $boostCat + $boostColor) * 100);
            }

            $url = url('/images/placeholder-item.jpg');
            if ($f->image_path && Storage::disk('public')->exists($f->image_path)) {
                $url = url(Storage::url($f->image_path));
            }

            $results->push([
                'id' => $item->id,
                'name' => $item->title,
                'match_percentage' => $matchPct,
                'image_url' => $url,
                'location' => $item->location ?? 'Unknown',
                'found_date' => $item->created_at->format('M j, Y'),
                'last_seen' => $item->updated_at->diffForHumans(),
                'color' => $f->color ?? 'Unknown',
                'feature1' => $f->category ?? null,
                'feature2' => null,
            ]);
        }

        return $results->sortByDesc('match_percentage')->take(8);
    }
}
