<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterQualityLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cultivation_cycle_id', 'pond_id', 'user_id', 'ph', 'temperature_c',
        'dissolved_oxygen', 'ammonia', 'nitrite', 'turbidity', 'alkalinity',
        'salinity', 'visual_condition', 'odor', 'notes', 'photo_path', 'measured_at',
    ];

    protected function casts(): array
    {
        return [
            'ph' => 'decimal:2',
            'temperature_c' => 'decimal:2',
            'dissolved_oxygen' => 'decimal:2',
            'ammonia' => 'decimal:3',
            'nitrite' => 'decimal:3',
            'turbidity' => 'decimal:2',
            'alkalinity' => 'decimal:2',
            'salinity' => 'decimal:2',
            'measured_at' => 'datetime',
        ];
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CultivationCycle::class, 'cultivation_cycle_id');
    }

    public function pond(): BelongsTo
    {
        return $this->belongsTo(Pond::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
