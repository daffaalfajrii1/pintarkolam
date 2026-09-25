<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $fillable = ['key', 'name', 'is_enabled', 'description'];

    protected function casts(): array
    {
        return ['is_enabled' => 'boolean'];
    }
}
