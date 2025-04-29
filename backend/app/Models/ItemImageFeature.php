<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImageFeature extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'image_path',
        'feature_vector',
        'classifications',
        'category',
        'color',
        'brand',
        'phash'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'feature_vector' => 'array',
        'classifications' => 'array',
    ];
    
    /**
     * Get the item that owns the image feature.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
