<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PondResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $canExact = $user && ($user->id === $this->user_id || $user->hasRole('admin'));
        $hide = $this->hide_exact_location && ! $canExact;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'area_m2' => $this->area_m2,
            'depth_m' => $this->depth_m,
            'volume_m3' => $this->volume_m3,
            'latitude' => $hide ? ($this->latitude ? round((float) $this->latitude, 2) : null) : $this->latitude,
            'longitude' => $hide ? ($this->longitude ? round((float) $this->longitude, 2) : null) : $this->longitude,
            'hide_exact_location' => $this->hide_exact_location,
            'notes' => $this->notes,
            'status' => $this->status,
            'latest_health_score' => $this->whenLoaded('latestHealthScore', function () {
                return [
                    'score' => $this->latestHealthScore?->score,
                    'category' => $this->latestHealthScore?->category,
                    'factors' => $this->latestHealthScore?->factors,
                    'calculated_at' => $this->latestHealthScore?->calculated_at?->toISOString(),
                ];
            }),
            'photos' => PondPhotoResource::collection($this->whenLoaded('photos')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
