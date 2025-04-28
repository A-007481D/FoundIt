<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Report;

class ReportCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected Report $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'report_id' => $this->report->id,
            'reportable_type' => class_basename($this->report->reportable_type),
            'reportable_id' => $this->report->reportable_id,
            'reason' => $this->report->reason,
            'reporter' => [
                'id' => $this->report->reporter->id,
                'name' => $this->report->reporter->firstname . ' ' . $this->report->reporter->lastname,
            ],
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
