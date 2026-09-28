<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Support\AppDateTime;

class WaterQualityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cultivation_cycle_id' => $this->cultivation_cycle_id,
            'pond_id' => $this->pond_id,
            'ph' => $this->ph,
            'temperature_c' => $this->temperature_c,
            'dissolved_oxygen' => $this->dissolved_oxygen,
            'visual_condition' => $this->visual_condition,
            'odor' => $this->odor,
            'notes' => $this->notes,
            'photo_url' => $this->photo_path ? Storage::disk('public')->url($this->photo_path) : null,
            'measured_at' => AppDateTime::iso($this->measured_at),
            'created_at' => AppDateTime::iso($this->created_at),
        ];
    }
}
