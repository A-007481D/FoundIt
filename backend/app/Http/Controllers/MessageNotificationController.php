<?php

namespace App\Http\Controllers;

use App\Models\MessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        
        try {
            $notification = MessageNotification::where('id', $notificationId)
                ->where('user_id', $user->id)
                ->firstOrFail();
            $notification->update(['read' => true]);
            return response()->json(['message' => 'Notification marked as read']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Notification not found'], 404);
        } catch (\Exception $e) {
            Log::error('MessageNotificationController@markAsRead error: ' . $e->getMessage());
            return response()->json(['message' => 'Could not mark notification as read'], 500);
        }
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        
        try {
            MessageNotification::where('user_id', $user->id)
                ->where('read', false)
                ->update(['read' => true]);
            return response()->json(['message' => 'All notifications marked as read']);
        } catch (\Exception $e) {
            Log::error('MessageNotificationController@markAllAsRead error: ' . $e->getMessage());
            return response()->json(['message' => 'Could not mark all notifications as read'], 500);
        }
    }
}
