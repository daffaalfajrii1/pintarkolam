<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $wa = preg_replace('/[^0-9]/', '', (string) $this->whatsapp);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'size_label' => $this->size_label,
            'stock_kg' => $this->stock_kg,
            'stock_pcs' => $this->stock_pcs,
            'price_unit' => $this->price_unit,
            'price' => $this->price,
            'min_order' => $this->min_order,
            'location_label' => $this->location_label,
            'description' => $this->description,
            'whatsapp' => $this->whatsapp,
            'whatsapp_link' => $wa ? 'https://wa.me/'.$wa.'?text='.urlencode('Halo, saya tertarik dengan produk '.$this->title.' di PintarKolam') : null,
            'availability' => $this->availability,
            'moderation_status' => $this->when($request->user()?->id === $this->user_id || $request->user()?->hasRole('admin'), $this->moderation_status),
            'is_published' => $this->is_published,
            'fish_species' => new FishSpeciesResource($this->whenLoaded('fishSpecies')),
            'farmer_profile' => new FarmerProfileResource($this->whenLoaded('farmerProfile')),
            'photos' => ProductPhotoResource::collection($this->whenLoaded('photos')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
