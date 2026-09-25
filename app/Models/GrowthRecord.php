<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthRecord extends Model
{
    protected $fillable = [
        'cultivation_cycle_id', 'user_id', 'sampled_at', 'sample_count',
        'avg_weight_gram', 'avg_length_cm', 'estimated_alive', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'sampled_at' => 'date',
            'avg_weight_gram' => 'decimal:2',
            'avg_length_cm' => 'decimal:2',
        ];
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CultivationCycle::class, 'cultivation_cycle_id');
    }
}
