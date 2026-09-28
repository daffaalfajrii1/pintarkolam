<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Support\AppDateTime;

class FeedingLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fed_at' => AppDateTime::iso($this->fed_at),
            'feed_type' => $this->feed_type,
            'amount_kg' => $this->amount_kg,
            'leftover_kg' => $this->leftover_kg,
            'status' => $this->status,
            'notes' => $this->notes,
        ];
    }
}
