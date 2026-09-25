<?php

namespace App\Policies;

use App\Models\CultivationCycle;
use App\Models\User;

class CultivationCyclePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'pembudidaya']);
    }

    public function view(User $user, CultivationCycle $cycle): bool
    {
        return $user->hasRole('admin') || $cycle->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('pembudidaya') || $user->hasRole('admin');
    }

    public function update(User $user, CultivationCycle $cycle): bool
    {
        return $user->hasRole('admin') || $cycle->user_id === $user->id;
    }

    public function delete(User $user, CultivationCycle $cycle): bool
    {
        return $this->update($user, $cycle);
    }
}
