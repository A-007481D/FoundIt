<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\ItemMatch;

class NewMatchFound extends Notification implements ShouldQueue
{
    use Queueable;

    protected $match;

    public function __construct(ItemMatch $match)
    {
        $this->match = $match;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'match_id' => $this->match->id,
            'lost_item_id' => $this->match->lost_item_id,
            'found_item_id' => $this->match->found_item_id,
            'matched_at' => now()->toDateTimeString(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => $this->toArray($notifiable),
        ]);
    }
}
