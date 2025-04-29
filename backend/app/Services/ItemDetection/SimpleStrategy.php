<?php

namespace App\Services\ItemDetection;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use App\Models\ItemImageFeature;
use Illuminate\Support\Facades\Storage;

class SimpleStrategy implements DetectiveStrategyInterface
{
    /**
     * Basic color/category matching strategy
     */
    public function search(UploadedFile $image, array $options = []): Collection
    {
        $path = $image->getRealPath();
        [$width, $height] = getimagesize($path);
        $type = exif_imagetype($path);
        switch ($type) {
            case IMAGETYPE_JPEG:
                $img = imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_PNG:
                $img = imagecreatefrompng($path);
                break;
            case IMAGETYPE_GIF:
                $img = imagecreatefromgif($path);
                break;
            default:
                $img = imagecreatefromstring(file_get_contents($path));
        }
        $x = (int) ($width / 2);
        $y = (int) ($height / 2);
        $rgb = imagecolorat($img, $x, $y);
        imagedestroy($img);

        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        $color = ($r > $g && $r > $b) ? 'Red' : (($g > $b) ? 'Green' : 'Blue');
        $category = match ($color) {
            'Red' => 'Accessory',
            'Green' => 'Clothing',
            'Blue' => 'Electronics',
            default => 'Unknown',
        };

        $items = ItemImageFeature::with('item')
            ->whereHas('item', fn($q) => $q->where('type', 'found')->where('status', 'active'))
            ->get();

        return $items->map(function ($f) use ($category, $color) {
            $item = $f->item;
            $baseScore = 30;
            $catBoost = ($f->category === $category) ? 40 : 0;
            $colorBoost = ($f->color === $color) ? 30 : 0;
            if ($colorBoost === 0 && (stripos($f->color, $color) !== false || stripos($color, $f->color) !== false)) {
                $colorBoost = 15;
            }
            $matchPct = min(99, $baseScore + $catBoost + $colorBoost);

            $url = $f->image_path && Storage::disk('public')->exists($f->image_path)
                ? url(Storage::url($f->image_path))
                : url('/images/placeholder-item.jpg');

            return [
                'id' => $item->id,
                'name' => $item->title,
                'match_percentage' => $matchPct,
                'image_url' => $url,
                'location' => $item->location ?? 'Unknown',
                'found_date' => $item->created_at->format('M j, Y'),
                'last_seen' => $item->updated_at->diffForHumans(),
                'color' => $f->color,
                'feature1' => $f->category,
                'feature2' => null,
            ];
        })->sortByDesc('match_percentage')->values()->take(8);
    }
}
