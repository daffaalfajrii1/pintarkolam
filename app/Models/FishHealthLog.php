<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FishHealthLog extends Model
{
    protected $fillable = [
        'cultivation_cycle_id', 'user_id', 'observed_at', 'symptoms',
        'affected_count', 'death_count', 'photo_path', 'suspected_cause',
        'action_taken', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return ['observed_at' => 'datetime'];
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CultivationCycle::class, 'cultivation_cycle_id');
    }
}
