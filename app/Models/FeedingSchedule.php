<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeedingSchedule extends Model
{
    protected $fillable = [
        'cultivation_cycle_id', 'feed_time', 'feed_type', 'amount_kg', 'is_active', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount_kg' => 'decimal:3',
            'is_active' => 'boolean',
        ];
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CultivationCycle::class, 'cultivation_cycle_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(FeedingLog::class);
    }
}
