<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'found_item_id',
        'user_id',
        'proof_description',
        'additional_details',
        'status',
        'admin_notes',
    ];

    public function foundItem()
    {
        return $this->belongsTo(FoundItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
