<?php

namespace App\Services;

use App\Models\CultivationCycle;
use App\Models\HarvestEstimate;
use Carbon\Carbon;

class HarvestEstimateService
{
    /** Asumsi survival awal sebelum ada data kematian/sampling. */
    public const DEFAULT_SURVIVAL_RATE = 90.0;

    public function calculate(CultivationCycle $cycle): HarvestEstimate
    {
        $cycle->loadMissing(['fishSpecies', 'growthRecords', 'mortalityLogs', 'feedingLogs']);

        $deaths = (int) $cycle->mortalityLogs()->sum('death_count');
        $alive = max(0, (int) $cycle->seed_count - $deaths);
        $survivalRate = $cycle->seed_count > 0
            ? round(($alive / $cycle->seed_count) * 100, 2)
            : 0;

        $latestGrowth = $cycle->growthRecords()->latest('sampled_at')->first();
        $avgWeightGram = $latestGrowth?->avg_weight_gram
            ?? $cycle->initial_size_gram
            ?? 5;

        if ($latestGrowth?->estimated_alive) {
            $alive = (int) $latestGrowth->estimated_alive;
            $survivalRate = $cycle->seed_count > 0
                ? round(($alive / $cycle->seed_count) * 100, 2)
                : 0;
        }

        $targetWeight = (float) ($cycle->target_size_gram
            ?? $cycle->fishSpecies?->typical_harvest_weight_gram
            ?? 250);

        $typicalDays = (int) ($cycle->fishSpecies?->typical_harvest_days ?? 120);
        $estimatedDate = $cycle->target_harvest_date
            ?? Carbon::parse($cycle->stocking_date)->addDays($typicalDays);

        // Belum ada sampling: proyeksikan ke ukuran panen target + survival default.
        $isProjection = ! $latestGrowth;
        if ($isProjection) {
            $projectedAlive = (int) round($cycle->seed_count * (self::DEFAULT_SURVIVAL_RATE / 100));
            if ($deaths > 0) {
                $projectedAlive = $alive;
                $survivalRate = $cycle->seed_count > 0
                    ? round(($alive / $cycle->seed_count) * 100, 2)
                    : self::DEFAULT_SURVIVAL_RATE;
            } else {
                $survivalRate = self::DEFAULT_SURVIVAL_RATE;
            }
            $weightForKg = $targetWeight;
            $fishCount = $projectedAlive;
        } else {
            $weightForKg = (float) $avgWeightGram;
            $fishCount = $alive;
        }

        $totalKg = round(($fishCount * $weightForKg) / 1000, 2);
        $pricePerKg = 28000;
        $value = round($totalKg * $pricePerKg, 2);

        $progress = $targetWeight > 0 ? ((float) $avgWeightGram / $targetWeight) : 0;
        $daysLeft = now()->startOfDay()->diffInDays(Carbon::parse($estimatedDate)->startOfDay(), false);

        $readiness = match (true) {
            $progress >= 0.95 || $daysLeft <= 0 => 'ready',
            $progress >= 0.75 || $daysLeft <= 14 => 'near',
            default => 'not_ready',
        };

        if ($readiness === 'near' && in_array($cycle->status, ['active', 'preparation'], true)) {
            $cycle->update(['status' => 'near_harvest']);
        }

        return HarvestEstimate::create([
            'cultivation_cycle_id' => $cycle->id,
            'estimated_harvest_date' => $estimatedDate,
            'estimated_fish_count' => $fishCount,
            'estimated_total_weight_kg' => $totalKg,
            'estimated_value' => $value,
            'survival_rate' => $survivalRate,
            'readiness_status' => $readiness,
            'assumptions' => [
                'avg_weight_gram' => (float) $avgWeightGram,
                'weight_used_gram' => $weightForKg,
                'target_weight_gram' => $targetWeight,
                'price_per_kg' => $pricePerKg,
                'deaths' => $deaths,
                'is_projection' => $isProjection,
                'typical_harvest_days' => $typicalDays,
                'species' => $cycle->fishSpecies?->name,
                'formula' => $isProjection
                    ? 'seed_count * survival_rate * target_weight_gram / 1000'
                    : 'alive * avg_weight_gram / 1000',
            ],
            'calculated_at' => now(),
        ]);
    }

    /**
     * Isi target panen & ukuran dari spesies, lalu hitung perkiraan.
     */
    public function bootstrapForNewCycle(CultivationCycle $cycle): HarvestEstimate
    {
        $cycle->loadMissing('fishSpecies');
        $species = $cycle->fishSpecies;

        $updates = [];
        if (empty($cycle->target_size_gram) && $species?->typical_harvest_weight_gram) {
            $updates['target_size_gram'] = $species->typical_harvest_weight_gram;
        }
        if (empty($cycle->target_harvest_date) && $species?->typical_harvest_days && $cycle->stocking_date) {
            $updates['target_harvest_date'] = Carbon::parse($cycle->stocking_date)
                ->addDays((int) $species->typical_harvest_days)
                ->toDateString();
        }
        if ($updates) {
            $cycle->update($updates);
            $cycle->refresh();
        }

        return $this->calculate($cycle);
    }
}
