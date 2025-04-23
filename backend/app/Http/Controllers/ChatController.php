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
}
