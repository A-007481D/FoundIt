<?php

namespace App\Jobs;

use App\Models\Item;
use App\Services\MatchingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessItemMatching implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Item $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * Handle the event and process matching.
     *
     * @param  mixed  $event  The dispatched event containing the item.
     */
    public function handle($event)
    {
        app(MatchingService::class)->matchItem($event->item);
    }
}
