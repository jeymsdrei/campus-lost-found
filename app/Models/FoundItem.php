<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoundItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'description',
        'category',
        'date_found',
        'location',
        'image',
        'status',
    ];

    protected $casts = [
        'date_found' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function claimRequests()
    {
        return $this->hasMany(ClaimRequest::class);
    }

    public function matchedLostItems()
    {
        return $this->belongsToMany(LostItem::class, 'item_matches')
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
