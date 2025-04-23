<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getConversations()
    {
        $user = Auth::user();

        $conversations = Conversation::where('user_id', $user->id)
            ->orWhere('other_user_id', $user->id)
            ->with(['user', 'otherUser', 'lastMessage'])
            ->get()
            ->map(function ($conversation) use ($user) {
                $otherUser = $conversation->user_id === $user->id
                    ? $conversation->otherUser
                    : $conversation->user;

                return [
                    'id' => $conversation->id,
                    'other_user' => [
                        'id' => $otherUser->id,
                        'name' => $otherUser->firstname . ' ' . $otherUser->lastname,
                        'photo' => $otherUser->profile_photo
                    ],
                    'last_message' => $conversation->lastMessage,
                    'unread_count' => $conversation->unreadMessages()->count()
                ];
            });

        return response()->json($conversations);
    }

    public function getMessages($conversationId)
    {
        $user = Auth::user();
        $conversation = Conversation::findOrFail($conversationId);

        // check if user is part of the conversation
        if ($conversation->user_id !== $user->id && $conversation->other_user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->firstname . ' ' . $message->sender->lastname
                    ],
                    'created_at' => $message->created_at,
                    'is_read' => $message->is_read
                ];
            });

        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }
}
