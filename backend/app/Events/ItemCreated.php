<?php

namespace App\Events;

use App\Models\Item;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemCreated
{
    use Dispatchable, SerializesModels;

    public Item $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }
}
