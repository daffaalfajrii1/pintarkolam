<?php

namespace App\Services;

use App\Models\CultivationCycle;
use App\Models\HarvestEstimate;
use Carbon\Carbon;

class HarvestEstimateService
{
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

        $totalKg = round(($alive * (float) $avgWeightGram) / 1000, 2);
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
            'estimated_fish_count' => $alive,
            'estimated_total_weight_kg' => $totalKg,
            'estimated_value' => $value,
            'survival_rate' => $survivalRate,
            'readiness_status' => $readiness,
            'assumptions' => [
                'avg_weight_gram' => (float) $avgWeightGram,
                'target_weight_gram' => $targetWeight,
                'price_per_kg' => $pricePerKg,
                'deaths' => $deaths,
                'formula' => 'alive * avg_weight_gram / 1000',
            ],
            'calculated_at' => now(),
        ]);
    }
}
