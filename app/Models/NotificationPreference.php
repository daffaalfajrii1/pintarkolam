<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id', 'water_critical', 'feeding_reminder', 'measurement_reminder',
        'harvest_near', 'new_products', 'announcements',
    ];

    protected function casts(): array
    {
        return [
            'water_critical' => 'boolean',
            'feeding_reminder' => 'boolean',
            'measurement_reminder' => 'boolean',
            'harvest_near' => 'boolean',
            'new_products' => 'boolean',
            'announcements' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
