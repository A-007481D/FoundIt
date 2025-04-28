<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Events\ItemCreated;
use App\Events\ItemUpdated;
use App\Services\MatchService;

class ItemService
{
    protected MatchService $matchService;

    public function __construct(MatchService $matchService)
    {
        $this->matchService = $matchService;
    }

    public function create(array $data, ?UploadedFile $image, int $userId): Item
    {
        $item = new Item();
        $item->title = $data['title'];
        $item->description = $data['description'];
        $item->type = $data['type'];
        $item->status = 'active';
        $item->location = $data['location'];
        $item->user_id = $userId;
        $item->category_id = $data['category_id'];

        if ($image && $image->isValid()) {
            $path = $image->store('items', 'public');
            $item->image = asset('storage/' . $path);
        }

        if ($data['type'] === 'lost') {
            $item->lost_date  = $data['lost_date'];
            $item->found_date = $data['found_date'] ?? now();
        } else {
            $item->found_date = $data['found_date'];
            $item->lost_date  = $data['lost_date'] ?? now();
        }

        $item->save();

        try {
            event(new ItemCreated($item));
            $this->matchService->process($item);
        } catch (\Throwable $e) {
            Log::warning('ItemService create matching failed', ['error' => $e->getMessage()]);
        }

        return $item;
    }

    public function update(int $id, array $data, ?UploadedFile $image, int $userId): Item
    {
        $item = Item::findOrFail($id);
        if ($item->user_id !== $userId) {
            abort(403, 'Unauthorized to update this item');
        }

        if ($image && $image->isValid()) {
            if ($item->image) {
                $old = str_replace(asset('storage/'), '', $item->image);
                Storage::disk('public')->delete($old);
            }
            $path = $image->store('items', 'public');
            $item->image = asset('storage/' . $path);
        }

        foreach (['title', 'description', 'location', 'category_id', 'lost_date', 'found_date'] as $field) {
            if (array_key_exists($field, $data)) {
                $item->{$field} = $data[$field];
            }
        }

        $item->save();

        try {
            event(new ItemUpdated($item));
            $this->matchService->process($item);
        } catch (\Throwable $e) {
            Log::warning('ItemService update matching failed', ['error' => $e->getMessage()]);
        }

        return $item;
    }

    public function archive(int $id, int $userId): Item
    {
        $item = Item::findOrFail($id);
        if ($item->user_id !== $userId) {
            abort(403, 'Unauthorized to archive this item');
        }
        $item->status = 'archived';
        $item->save();

        return $item;
    }

    public function restore(int $id, int $userId): Item
    {
        $item = Item::findOrFail($id);
        if ($item->user_id !== $userId) {
            abort(403, 'Unauthorized to restore this item');
        }
        if ($item->status !== 'archived') {
            abort(400, 'Only archived items can be restored');
        }
        $item->status = 'active';
        $item->save();

        return $item;
    }
}
