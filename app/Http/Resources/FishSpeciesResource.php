<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FishSpeciesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'scientific_name' => $this->scientific_name,
            'description' => $this->description,
            'typical_harvest_days' => $this->typical_harvest_days,
            'typical_harvest_weight_gram' => $this->typical_harvest_weight_gram,
        ];
    }
}
