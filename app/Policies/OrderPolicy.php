<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // Admin can view all
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            return true;
        }

        // Customer can view their own orders
        if ($user->hasRole('customer') && $order->user_id === $user->id) {
            return true;
        }

        // Seller can view order if it contains their products
        if ($user->hasRole('seller')) {
            return $order->items()->where('seller_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            return true;
        }

        // Seller can update order (e.g. shipping status) if it contains their products
        if ($user->hasRole('seller')) {
            return $order->items()->where('seller_id', $user->id)->exists();
        }

        return false;
    }
}
