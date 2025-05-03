<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use App\Services\ActivityLogService;

class UserSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token',
        'ip_address',
        'user_agent',
        'device_info',
        'last_activity_at',
        'expires_at',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_activity_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active sessions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include sessions for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Update the last activity time.
     */
    public function updateLastActivity()
    {
        $this->last_activity_at = Carbon::now();
        return $this->save();
    }

    /**
     * Terminate this session.
     * 
     * @param bool $logActivity Whether to log this activity
     * @return bool
     */
    public function terminate($logActivity = true)
    {
        $this->is_active = false;
        $this->save();

        if ($logActivity) {
            // Log session termination
            app(ActivityLogService::class)->log(
                'session_terminated',
                null,
                null,
                ['session_id' => $this->id, 'terminated_user_id' => $this->user_id],
                auth()->check() ? auth()->id() : null
            );
        }
        
        return true;
    }
    
    /**
     * Check if this session is associated with the given token
     * 
     * @param string $token The JWT token to check
     * @return bool
     */
    public function matchesToken($token)
    {
        return $this->token === hash('sha256', $token);
    }

    /**
     * Determine if the session is expired.
     */
    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }
}
