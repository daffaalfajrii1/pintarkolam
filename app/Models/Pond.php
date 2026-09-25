<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Pond extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'farmer_profile_id', 'name', 'type', 'area_m2', 'depth_m',
        'volume_m3', 'latitude', 'longitude', 'hide_exact_location', 'notes', 'status', 'public_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (Pond $pond) {
            if (! $pond->public_token) {
                $pond->public_token = (string) Str::uuid();
            }
        });
    }

    public function ensurePublicToken(): string
    {
        if (! $this->public_token) {
            $this->forceFill(['public_token' => (string) Str::uuid()])->save();
        }

        return $this->public_token;
    }

    protected function casts(): array
    {
        return [
            'area_m2' => 'decimal:2',
            'depth_m' => 'decimal:2',
            'volume_m3' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'hide_exact_location' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function farmerProfile(): BelongsTo
    {
        return $this->belongsTo(FarmerProfile::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PondPhoto::class);
    }

    public function cycles(): HasMany
    {
        return $this->hasMany(CultivationCycle::class);
    }

    public function healthScores(): HasMany
    {
        return $this->hasMany(PondHealthScore::class);
    }

    public function latestHealthScore()
    {
        return $this->hasOne(PondHealthScore::class)->latestOfMany('calculated_at');
    }
}
