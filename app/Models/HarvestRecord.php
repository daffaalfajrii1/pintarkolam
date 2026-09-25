<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HarvestRecord extends Model
{
    protected $fillable = [
        'cultivation_cycle_id', 'user_id', 'harvested_at', 'fish_count',
        'total_weight_kg', 'avg_weight_gram', 'selling_price', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'harvested_at' => 'date',
            'total_weight_kg' => 'decimal:2',
            'avg_weight_gram' => 'decimal:2',
            'selling_price' => 'decimal:2',
        ];
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CultivationCycle::class, 'cultivation_cycle_id');
    }
}
