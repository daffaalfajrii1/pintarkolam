<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MortalityLog extends Model
{
    protected $fillable = [
        'cultivation_cycle_id', 'user_id', 'recorded_at', 'death_count',
        'suspected_cause', 'notes',
    ];

    protected function casts(): array
    {
        return ['recorded_at' => 'date'];
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CultivationCycle::class, 'cultivation_cycle_id');
    }
}
