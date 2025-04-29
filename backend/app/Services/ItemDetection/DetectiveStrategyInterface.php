<?php

namespace App\Services\ItemDetection;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface DetectiveStrategyInterface
{
    /**
     * Search for matching items based on the uploaded image.
     *
     * @param UploadedFile $image
     * @param array $options  Optional parameters (e.g. features, classifications)
     * @return Collection   Collection of match result arrays.
     */
    public function search(UploadedFile $image, array $options = []): Collection;
}
