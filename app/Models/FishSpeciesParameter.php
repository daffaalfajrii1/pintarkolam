<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FishSpeciesParameter extends Model
{
    protected $fillable = [
        'fish_species_id', 'code', 'name', 'unit',
        'min_value', 'max_value', 'ideal_min', 'ideal_max',
        'weight', 'is_active', 'description',
    ];

    protected function casts(): array
    {
        return [
            'min_value' => 'decimal:3',
            'max_value' => 'decimal:3',
            'ideal_min' => 'decimal:3',
            'ideal_max' => 'decimal:3',
            'is_active' => 'boolean',
        ];
    }

    public function fishSpecies(): BelongsTo
    {
        return $this->belongsTo(FishSpecies::class);
    }
}
