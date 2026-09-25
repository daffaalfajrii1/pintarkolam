<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HarvestEstimate extends Model
{
    protected $fillable = [
        'cultivation_cycle_id', 'estimated_harvest_date', 'estimated_fish_count',
        'estimated_total_weight_kg', 'estimated_value', 'survival_rate',
        'readiness_status', 'assumptions', 'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'estimated_harvest_date' => 'date',
            'estimated_total_weight_kg' => 'decimal:2',
            'estimated_value' => 'decimal:2',
            'survival_rate' => 'decimal:2',
            'assumptions' => 'array',
            'calculated_at' => 'datetime',
        ];
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CultivationCycle::class, 'cultivation_cycle_id');
    }
}
