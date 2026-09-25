<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecommendationRule extends Model
{
    protected $fillable = [
        'fish_species_id', 'parameter_code', 'phase', 'min_value', 'max_value',
        'severity', 'title', 'advice', 'priority', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_value' => 'decimal:3',
            'max_value' => 'decimal:3',
            'is_active' => 'boolean',
        ];
    }

    public function fishSpecies(): BelongsTo
    {
        return $this->belongsTo(FishSpecies::class);
    }
}
