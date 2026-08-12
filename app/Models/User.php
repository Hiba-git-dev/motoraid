<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens; // Si vous utilisez Sanctum plus tard

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,Billable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'riding_style',
        'bio',
        'role',
        'emergency_name',
        'emergency_phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relations
    public function motorcycles()
    {
        return $this->hasMany(Motorcycle::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'renter_id');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    public function ownedGroups()
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function liveTracking()
    {
        return $this->hasOne(LiveTracking::class);
    }

    // Helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}