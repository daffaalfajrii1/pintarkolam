<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmerProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'business_name', 'shop_name', 'owner_name', 'address', 'village', 'district',
        'regency', 'province', 'latitude', 'longitude', 'hide_exact_location',
        'whatsapp', 'bio', 'shop_logo_path', 'shop_cover_path', 'shop_description',
        'verification_status', 'storefront_status',
        'verified_at', 'verified_by', 'rejection_reason', 'storefront_rejection_reason',
    ];

    public function canSell(): bool
    {
        return $this->verification_status === 'approved'
            && $this->storefront_status === 'approved';
    }

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'hide_exact_location' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ponds(): HasMany
    {
        return $this->hasMany(Pond::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
