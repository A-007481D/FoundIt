<?php

namespace App\Http\Controllers;

use App\Models\MessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageNotificationController extends Controller
{
    public function getNotifications()
    {
        $user = Auth::user();
        
        $notifications = MessageNotification::where('user_id', $user->id)
            ->where('read', false)
            ->with(['message.sender'])
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => [
                        'id' => $notification->message->id,
                        'content' => $notification->message->content,
                        'sender' => [
                            'id' => $notification->message->sender->id,
                            'name' => $notification->message->sender->firstname . ' ' . $notification->message->sender->lastname
                        ]
                    ],
                    'created_at' => $notification->created_at
                ];
            });
        
        return response()->json($notifications);
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        
        $notification = MessageNotification::where('id', $notificationId)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        $notification->update(['read' => true]);
        
        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        
        MessageNotification::where('user_id', $user->id)
            ->where('read', false)
            ->update(['read' => true]);
            
        return response()->json(['message' => 'All notifications marked as read']);
    }
}
