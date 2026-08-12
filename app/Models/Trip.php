<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'distance_km',
        'estimated_duration',
        'is_public',
        'gpx_file_url',
        'route_data'
    ];

    protected $casts = [
        'distance_km' => 'decimal:2',
        'estimated_duration' => 'integer',
        'is_public' => 'boolean',
        'route_data' => 'array', // Permet à Laravel de gérer le JSON automatiquement
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function routePoints(): HasMany
    {
        return $this->hasMany(RoutePoint::class)->orderBy('order', 'asc');
    }
}