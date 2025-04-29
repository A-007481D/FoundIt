<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Item extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
        'visible',
        'location',
        'image',
        'user_id',
        'category_id',
        'found_date',
        'lost_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'found_date' => 'datetime',
        'lost_date' => 'datetime',
        'visible' => 'boolean',
    ];

    /**
     * Get the user that owns the item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that the item belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the reports for the item.
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Get the features for the item.
     */
    public function features()
    {
        return $this->hasMany(ItemImageFeature::class);
    }

    /**
     * Get the images for the item.
     */
    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    /**
     * Specify which model attributes are searchable via Scout/Meili.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'location' => $this->location,
        ];
    }
}
