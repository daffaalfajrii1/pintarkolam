<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'farmer_profile_id', 'fish_species_id', 'cultivation_cycle_id',
        'title', 'size_label', 'stock_kg', 'stock_pcs', 'price_unit', 'price',
        'min_order', 'location_label', 'description', 'whatsapp', 'availability',
        'moderation_status', 'rejection_reason', 'moderated_by', 'moderated_at', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'stock_kg' => 'decimal:2',
            'price' => 'decimal:2',
            'min_order' => 'decimal:2',
            'is_published' => 'boolean',
            'moderated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function farmerProfile(): BelongsTo
    {
        return $this->belongsTo(FarmerProfile::class);
    }

    public function fishSpecies(): BelongsTo
    {
        return $this->belongsTo(FishSpecies::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ProductPhoto::class);
    }
}
