<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MortalityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cultivation_cycle_id' => $this->cultivation_cycle_id,
            'recorded_at' => $this->recorded_at?->toDateString(),
            'death_count' => $this->death_count,
            'suspected_cause' => $this->suspected_cause,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
