<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HarvestEstimateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'estimated_harvest_date' => $this->estimated_harvest_date?->toDateString(),
            'estimated_fish_count' => $this->estimated_fish_count,
            'estimated_total_weight_kg' => $this->estimated_total_weight_kg,
            'estimated_value' => $this->estimated_value,
            'survival_rate' => $this->survival_rate,
            'readiness_status' => $this->readiness_status,
            'assumptions' => $this->assumptions,
            'calculated_at' => $this->calculated_at?->toISOString(),
        ];
    }
}
