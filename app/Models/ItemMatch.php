<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'lost_item_id',
        'found_item_id',
        'match_score',
        'is_reviewed',
    ];

    protected $casts = [
        'match_score' => 'decimal:2',
        'is_reviewed' => 'boolean',
    ];

    public function lostItem()
    {
        return $this->belongsTo(LostItem::class);
    }

    public function foundItem()
    {
        return $this->belongsTo(FoundItem::class);
    }

    public function scopeUnreviewed($query)
    {
        return $query->where('is_reviewed', false);
    }
}
