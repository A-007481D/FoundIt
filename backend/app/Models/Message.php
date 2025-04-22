<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Conversation;
use App\Models\MessageReport;
use App\Models\MessageNotification;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'content',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function reports()
    {
        return $this->hasMany(MessageReport::class);
    }

    public function notifications()
    {
        return $this->hasMany(MessageNotification::class);
    }
}
