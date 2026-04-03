<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'department',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function lostItems()
    {
        return $this->hasMany(LostItem::class);
    }

    public function foundItems()
    {
        return $this->hasMany(FoundItem::class);
    }

    public function claimRequests()
    {
        return $this->hasMany(ClaimRequest::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
