<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrowthRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sampled_at' => $this->sampled_at?->toDateString(),
            'sample_count' => $this->sample_count,
            'avg_weight_gram' => $this->avg_weight_gram,
            'avg_length_cm' => $this->avg_length_cm,
            'estimated_alive' => $this->estimated_alive,
            'notes' => $this->notes,
        ];
    }
}
