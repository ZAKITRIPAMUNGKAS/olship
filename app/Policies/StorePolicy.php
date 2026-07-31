<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;

class StorePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Store $store): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasRole('seller') && $store->seller_id === $user->id;
    }
}
