<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reason',
        'details',
        'status',
        'resolution',
        'reporter_id',
        'reportable_id',
        'reportable_type',
    ];

    /**
     * Get the user who reported.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Get the reportable model (user or item).
     */
    public function reportable()
    {
        return $this->morphTo();
    }
}
