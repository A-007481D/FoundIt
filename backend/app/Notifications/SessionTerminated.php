<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionTerminated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $adminName;
    protected $allSessions;

    /**
     * Create a new notification instance.
     *
     * @param string $adminName Name of the admin who terminated the session
     * @param bool $allSessions Whether all sessions were terminated
     * @return void
     */
    public function __construct($adminName, $allSessions = false)
    {
        $this->adminName = $adminName;
        $this->allSessions = $allSessions;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = $this->allSessions 
            ? "All your active sessions have been terminated by administrator {$this->adminName}." 
            : "One of your sessions has been terminated by administrator {$this->adminName}.";
            
        return (new MailMessage)
            ->subject('Session Terminated')
            ->line($message)
            ->line('If you did not expect this action, please contact support.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'session_terminated',
            'message' => $this->allSessions 
                ? "All your active sessions have been terminated by administrator {$this->adminName}" 
                : "One of your sessions has been terminated by administrator {$this->adminName}",
            'admin_name' => $this->adminName,
            'all_sessions' => $this->allSessions,
            'timestamp' => now()->toIso8601String()
        ];
    }
}
