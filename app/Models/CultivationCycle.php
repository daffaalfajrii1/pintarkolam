<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CultivationCycle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pond_id', 'user_id', 'fish_species_id', 'name', 'stocking_date',
        'seed_count', 'initial_size_gram', 'seed_source', 'target_size_gram',
        'target_harvest_date', 'status', 'notes',
        'seed_cost', 'feed_cost', 'electricity_cost', 'medicine_cost',
        'other_cost', 'estimated_revenue',
    ];

    protected function casts(): array
    {
        return [
            'stocking_date' => 'date',
            'target_harvest_date' => 'date',
            'initial_size_gram' => 'decimal:2',
            'target_size_gram' => 'decimal:2',
            'seed_cost' => 'decimal:2',
            'feed_cost' => 'decimal:2',
            'electricity_cost' => 'decimal:2',
            'medicine_cost' => 'decimal:2',
            'other_cost' => 'decimal:2',
            'estimated_revenue' => 'decimal:2',
        ];
    }

    public function pond(): BelongsTo
    {
        return $this->belongsTo(Pond::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fishSpecies(): BelongsTo
    {
        return $this->belongsTo(FishSpecies::class);
    }

    public function waterQualityLogs(): HasMany
    {
        return $this->hasMany(WaterQualityLog::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function feedingSchedules(): HasMany
    {
        return $this->hasMany(FeedingSchedule::class);
    }

    public function feedingLogs(): HasMany
    {
        return $this->hasMany(FeedingLog::class);
    }

    public function growthRecords(): HasMany
    {
        return $this->hasMany(GrowthRecord::class);
    }

    public function mortalityLogs(): HasMany
    {
        return $this->hasMany(MortalityLog::class);
    }

    public function fishHealthLogs(): HasMany
    {
        return $this->hasMany(FishHealthLog::class);
    }

    public function harvestEstimate(): HasOne
    {
        return $this->hasOne(HarvestEstimate::class)->latestOfMany('calculated_at');
    }

    public function harvestRecords(): HasMany
    {
        return $this->hasMany(HarvestRecord::class);
    }

    public function costEntries(): HasMany
    {
        return $this->hasMany(CycleCostEntry::class);
    }

    public function totalDeaths(): int
    {
        return (int) $this->mortalityLogs()->sum('death_count');
    }

    public function aliveCount(): int
    {
        return max(0, (int) $this->seed_count - $this->totalDeaths());
    }

    public function totalFeedKg(): float
    {
        return (float) $this->feedingLogs()->sum('amount_kg');
    }

    public function latestAvgWeightGram(): float
    {
        $latest = $this->growthRecords()->latest('sampled_at')->first();

        return $latest ? (float) $latest->avg_weight_gram : 0.0;
    }

    public function estimatedBiomassKg(): float
    {
        $avg = $this->latestAvgWeightGram();
        if ($avg <= 0) {
            return 0.0;
        }

        return round(($this->aliveCount() * $avg) / 1000, 3);
    }

    public function initialBiomassKg(): float
    {
        $initialGram = 5;

        return round(($this->seed_count * $initialGram) / 1000, 3);
    }

    public function fcr(): ?float
    {
        $gain = $this->estimatedBiomassKg() - $this->initialBiomassKg();
        if ($gain <= 0 || $this->totalFeedKg() <= 0) {
            return null;
        }

        return round($this->totalFeedKg() / $gain, 2);
    }

    public function entriesCostTotal(): float
    {
        return (float) $this->costEntries()
            ->where('category', '!=', 'revenue')
            ->sum('amount');
    }

    public function entriesRevenueTotal(): float
    {
        return (float) $this->costEntries()
            ->where('category', 'revenue')
            ->sum('amount');
    }

    public function totalCost(): float
    {
        $fromEntries = $this->entriesCostTotal();
        if ($fromEntries > 0 || $this->costEntries()->where('category', '!=', 'revenue')->exists()) {
            return $fromEntries;
        }

        return (float) $this->seed_cost
            + (float) $this->feed_cost
            + (float) $this->electricity_cost
            + (float) $this->medicine_cost
            + (float) $this->other_cost;
    }

    public function totalRevenue(): float
    {
        $fromEntries = $this->entriesRevenueTotal();
        if ($fromEntries > 0 || $this->costEntries()->where('category', 'revenue')->exists()) {
            return $fromEntries;
        }

        return (float) $this->estimated_revenue;
    }

    public function netProfit(): float
    {
        return $this->totalRevenue() - $this->totalCost();
    }
}
