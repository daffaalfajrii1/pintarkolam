<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmerProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hide = $this->hide_exact_location && ! $this->canSeeExact($request);

        return [
            'id' => $this->id,
            'business_name' => $this->business_name,
            'owner_name' => $this->owner_name,
            'address' => $this->address,
            'village' => $this->village,
            'district' => $this->district,
            'regency' => $this->regency,
            'province' => $this->province,
            'latitude' => $hide ? $this->approx($this->latitude) : $this->latitude,
            'longitude' => $hide ? $this->approx($this->longitude) : $this->longitude,
            'hide_exact_location' => $this->hide_exact_location,
            'whatsapp' => $this->whatsapp,
            'bio' => $this->bio,
            'verification_status' => $this->verification_status,
            'storefront_status' => $this->storefront_status,
        ];
    }

    private function canSeeExact(Request $request): bool
    {
        $user = $request->user();
        if (! $user) {
            return false;
        }

        return $user->id === $this->user_id || $user->hasRole('admin');
    }

    private function approx($value)
    {
        return $value === null ? null : round((float) $value, 2);
    }
}
