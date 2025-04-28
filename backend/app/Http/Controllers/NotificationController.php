<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    // fetch all notifications
    public function index(Request $request)
    {
        $user = Auth::user();
        // Only show notifications from the past 30 days, paginated
        $perPage = $request->get('per_page', 20);
        $notifications = $user->notifications()
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        return response()->json($notifications);
    }

    public function markRead($id)
    {
        $user = Auth::user();
        try {
            $notification = $user->notifications()->findOrFail($id);
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Notification not found'], 404);
        } catch (\Exception $e) {
            Log::error('NotificationController@markRead error: ' . $e->getMessage());
            return response()->json(['message' => 'Could not mark notification as read'], 500);
        }
    }

    public function markAllRead()
    {
        $user = Auth::user();
        try {
            foreach ($user->unreadNotifications as $notif) {
                $notif->markAsRead();
            }
            return response()->json(['message' => 'All notifications marked as read']);
        } catch (\Exception $e) {
            Log::error('NotificationController@markAllRead error: ' . $e->getMessage());
            return response()->json(['message' => 'Could not mark all notifications as read'], 500);
        }
    }
}
