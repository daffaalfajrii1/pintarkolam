<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedingScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'feed_time' => is_string($this->feed_time)
                ? substr($this->feed_time, 0, 5)
                : optional($this->feed_time)->format('H:i'),
            'feed_type' => $this->feed_type,
            'amount_kg' => $this->amount_kg,
            'is_active' => $this->is_active,
            'notes' => $this->notes,
        ];
    }
}
