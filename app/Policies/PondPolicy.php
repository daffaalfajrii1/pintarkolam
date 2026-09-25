<?php

namespace App\Policies;

use App\Models\CultivationCycle;
use App\Models\Pond;
use App\Models\Product;
use App\Models\User;

class PondPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'pembudidaya']);
    }

    public function view(User $user, Pond $pond): bool
    {
        return $user->hasRole('admin') || $pond->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('pembudidaya') || $user->hasRole('admin');
    }

    public function update(User $user, Pond $pond): bool
    {
        return $user->hasRole('admin') || $pond->user_id === $user->id;
    }

    public function delete(User $user, Pond $pond): bool
    {
        return $this->update($user, $pond);
    }
}
