<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'description',
        'category',
        'date_lost',
        'location',
        'image',
        'status',
    ];

    protected $casts = [
        'date_lost' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matchedFoundItems()
    {
        return $this->belongsToMany(FoundItem::class, 'item_matches')
            ->withPivot('match_score', 'is_reviewed')
            ->withTimestamps();
    }

    public function itemMatches()
    {
        return $this->hasMany(ItemMatch::class);
    }

    public function scopeUnclaimed($query)
    {
        return $query->where('status', 'unclaimed');
    }
}
