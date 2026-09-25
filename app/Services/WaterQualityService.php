<?php

namespace App\Services;

use App\Models\CultivationCycle;
use App\Models\FishSpeciesParameter;
use App\Models\PondHealthScore;
use App\Models\Recommendation;
use App\Models\RecommendationRule;
use App\Models\WaterQualityLog;
use App\Models\WaterQualityParameter;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class WaterQualityService
{
    public function processLog(WaterQualityLog $log): PondHealthScore
    {
        $score = $this->calculateHealthScore($log);
        $this->generateRecommendations($log);

        return $score;
    }

    public function resolveParameters(?int $fishSpeciesId): Collection
    {
        $globals = WaterQualityParameter::query()
            ->where('is_active', true)
            ->get()
            ->keyBy('code');

        if (! $fishSpeciesId) {
            return $globals;
        }

        $speciesParams = FishSpeciesParameter::query()
            ->where('fish_species_id', $fishSpeciesId)
            ->where('is_active', true)
            ->get()
            ->keyBy('code');

        if ($speciesParams->isEmpty()) {
            return $globals;
        }

        // Species overrides win; fill missing codes from global defaults.
        foreach ($globals as $code => $global) {
            if (! $speciesParams->has($code)) {
                $speciesParams->put($code, $global);
            }
        }

        return $speciesParams;
    }

    public function calculateHealthScore(WaterQualityLog $log): PondHealthScore
    {
        $speciesId = $log->cycle?->fish_species_id;
        $parameters = $this->resolveParameters($speciesId);
        $factors = [];
        $weighted = 0;
        $totalWeight = 0;

        $values = [
            'ph' => $log->ph,
            'temperature' => $log->temperature_c,
            'do' => $log->dissolved_oxygen,
        ];

        foreach ($values as $code => $value) {
            if ($value === null || ! $parameters->has($code)) {
                continue;
            }

            $param = $parameters[$code];
            $weight = max(1, (int) $param->weight);
            $totalWeight += $weight;

            $idealMin = (float) $param->ideal_min;
            $idealMax = (float) $param->ideal_max;
            $min = (float) ($param->min_value ?? $idealMin);
            $max = (float) ($param->max_value ?? $idealMax);

            if ($value >= $idealMin && $value <= $idealMax) {
                $paramScore = 100;
            } elseif ($value < $min || $value > $max) {
                $paramScore = 20;
                $factors[] = [
                    'code' => $code,
                    'message' => "{$param->name} di luar batas aman untuk ikan ini ({$value} {$param->unit})",
                    'impact' => 'critical',
                ];
            } else {
                $paramScore = 55;
                $factors[] = [
                    'code' => $code,
                    'message' => "{$param->name} kurang ideal untuk ikan ini ({$value} {$param->unit})",
                    'impact' => 'warning',
                ];
            }

            $weighted += $paramScore * $weight;
        }

        $hoursSince = Carbon::parse($log->measured_at)->diffInHours(now());
        if ($hoursSince > 48) {
            $factors[] = [
                'code' => 'stale',
                'message' => 'Pengukuran kualitas air sudah lebih dari 48 jam',
                'impact' => 'warning',
            ];
        }

        $mortality = $log->cycle?->mortalityLogs()->where('recorded_at', '>=', now()->subDays(7))->sum('death_count');
        if ($mortality > 0) {
            $factors[] = [
                'code' => 'mortality',
                'message' => "Terdapat catatan kematian ikan ({$mortality} ekor) dalam 7 hari terakhir",
                'impact' => 'critical',
            ];
        }

        $score = $totalWeight > 0 ? (int) round($weighted / $totalWeight) : 50;
        if ($mortality > 0) {
            $score = max(0, $score - 15);
        }
        if ($hoursSince > 48) {
            $score = max(0, $score - 10);
        }

        $category = match (true) {
            $score >= 80 => 'normal',
            $score >= 50 => 'waspada',
            default => 'kritis',
        };

        return PondHealthScore::create([
            'pond_id' => $log->pond_id,
            'cultivation_cycle_id' => $log->cultivation_cycle_id,
            'water_quality_log_id' => $log->id,
            'score' => $score,
            'category' => $category,
            'factors' => $factors,
            'calculated_at' => now(),
        ]);
    }

    public function generateRecommendations(WaterQualityLog $log): void
    {
        $cycle = $log->cycle;
        $speciesId = $cycle?->fish_species_id;

        $speciesRules = RecommendationRule::query()
            ->where('is_active', true)
            ->when($speciesId, fn ($q) => $q->where('fish_species_id', $speciesId), fn ($q) => $q->whereRaw('1=0'))
            ->orderBy('priority')
            ->get();

        $rules = $speciesRules->isNotEmpty()
            ? $speciesRules
            : RecommendationRule::query()
                ->where('is_active', true)
                ->whereNull('fish_species_id')
                ->orderBy('priority')
                ->get();

        $map = [
            'ph' => $log->ph,
            'temperature' => $log->temperature_c,
            'do' => $log->dissolved_oxygen,
        ];

        $created = 0;
        foreach ($rules as $rule) {
            $value = $map[$rule->parameter_code] ?? null;
            if ($value === null) {
                continue;
            }

            $inTrigger = true;
            if ($rule->min_value !== null && $rule->max_value !== null) {
                $inTrigger = $value >= (float) $rule->min_value && $value <= (float) $rule->max_value;
            } elseif ($rule->min_value !== null) {
                $inTrigger = $value >= (float) $rule->min_value;
            } elseif ($rule->max_value !== null) {
                $inTrigger = $value <= (float) $rule->max_value;
            }

            if (! $inTrigger) {
                continue;
            }

            Recommendation::create([
                'cultivation_cycle_id' => $log->cultivation_cycle_id,
                'water_quality_log_id' => $log->id,
                'recommendation_rule_id' => $rule->id,
                'severity' => $rule->severity,
                'title' => $rule->title,
                'advice' => $rule->advice,
            ]);
            $created++;
        }

        if ($created === 0) {
            $speciesName = $cycle?->fishSpecies?->name;
            Recommendation::create([
                'cultivation_cycle_id' => $log->cultivation_cycle_id,
                'water_quality_log_id' => $log->id,
                'severity' => 'info',
                'title' => $speciesName
                    ? "Kondisi air normal untuk {$speciesName}"
                    : 'Kondisi air normal',
                'advice' => $speciesName
                    ? "Kondisi air tampak sesuai untuk {$speciesName}. Lanjutkan pemantauan rutin."
                    : 'Kondisi air tampak normal. Lanjutkan pemantauan rutin.',
            ]);
        }
    }
}
