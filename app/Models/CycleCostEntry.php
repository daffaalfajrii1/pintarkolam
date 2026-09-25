<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CycleCostEntry extends Model
{
    public const CATEGORIES = [
        'seed' => 'Biaya benih',
        'feed' => 'Biaya pakan',
        'electricity' => 'Biaya listrik',
        'medicine' => 'Obat / perawatan',
        'other' => 'Biaya lain',
        'revenue' => 'Pendapatan',
    ];

    protected $fillable = [
        'cultivation_cycle_id', 'user_id', 'category', 'label',
        'amount', 'recorded_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'recorded_at' => 'date',
        ];
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(CultivationCycle::class, 'cultivation_cycle_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? ($this->label ?: $this->category);
    }

    public function isRevenue(): bool
    {
        return $this->category === 'revenue';
    }
}
