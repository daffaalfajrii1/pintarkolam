<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Product $product): bool
    {
        if ($product->is_published && $product->moderation_status === 'approved') {
            return true;
        }

        return $user && ($user->id === $product->user_id || $user->hasRole('admin'));
    }

    public function create(User $user): bool
    {
        return $user->hasRole('pembudidaya') || $user->hasRole('admin');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasRole('admin') || $product->user_id === $user->id;
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->update($user, $product);
    }
}
