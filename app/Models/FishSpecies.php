<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FishSpecies extends Model
{
    protected $fillable = [
        'name', 'slug', 'scientific_name', 'description',
        'typical_harvest_days', 'typical_harvest_weight_gram', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'typical_harvest_weight_gram' => 'decimal:2',
        ];
    }

    public function cycles(): HasMany
    {
        return $this->hasMany(CultivationCycle::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function waterParameters(): HasMany
    {
        return $this->hasMany(FishSpeciesParameter::class);
    }

    public function recommendationRules(): HasMany
    {
        return $this->hasMany(RecommendationRule::class);
    }
}
