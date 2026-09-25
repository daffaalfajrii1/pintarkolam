<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedingLog extends Model
{
    protected $fillable = [
        'cultivation_cycle_id', 'feeding_schedule_id', 'user_id', 'fed_at',
        'feed_type', 'amount_kg', 'leftover_kg', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'fed_at' => 'datetime',
            'amount_kg' => 'decimal:3',
            'leftover_kg' => 'decimal:3',
        ];
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CultivationCycle::class, 'cultivation_cycle_id');
    }
}
