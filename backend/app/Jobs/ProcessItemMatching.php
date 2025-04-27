<?php

namespace App\Jobs;

use App\Models\Item;
use App\Repositories\Contracts\MatcherInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessItemMatching implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function handle($event): void
    {
        app(MatcherInterface::class)->matchItem($event->item);
    }
}
