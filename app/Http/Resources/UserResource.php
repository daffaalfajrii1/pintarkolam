<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'roles' => $this->whenLoaded('roles', fn () => $this->getRoleNames()),
            'farmer_profile' => new FarmerProfileResource($this->whenLoaded('farmerProfile')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
