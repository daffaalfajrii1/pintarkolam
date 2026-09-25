<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CultivationCycleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'pond_id' => $this->pond_id,
            'pond' => new PondResource($this->whenLoaded('pond')),
            'fish_species' => new FishSpeciesResource($this->whenLoaded('fishSpecies')),
            'stocking_date' => $this->stocking_date?->toDateString(),
            'seed_count' => $this->seed_count,
            'initial_size_gram' => $this->initial_size_gram,
            // backward compatible alias for mobile clients
            'initial_size_cm' => $this->initial_size_gram,
            'seed_source' => $this->seed_source,
            'target_size_gram' => $this->target_size_gram,
            'target_harvest_date' => $this->target_harvest_date?->toDateString(),
            'status' => $this->status,
            'notes' => $this->notes,
            'harvest_estimate' => new HarvestEstimateResource($this->whenLoaded('harvestEstimate')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
