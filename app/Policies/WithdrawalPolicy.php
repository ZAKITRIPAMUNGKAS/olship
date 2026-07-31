<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Withdrawal;

class WithdrawalPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Withdrawal $withdrawal): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasRole('seller') && $withdrawal->seller_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Withdrawal $withdrawal): bool
    {
        return $user->hasRole('admin') || $user->hasRole('super_admin'); // Only admin can update withdrawal status
    }
}
