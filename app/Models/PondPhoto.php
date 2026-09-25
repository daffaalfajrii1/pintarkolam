<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PondPhoto extends Model
{
    protected $fillable = ['pond_id', 'path', 'caption', 'is_primary'];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function pond(): BelongsTo
    {
        return $this->belongsTo(Pond::class);
    }
}
