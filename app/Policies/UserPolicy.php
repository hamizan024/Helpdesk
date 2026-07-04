<?php

namespace App\Policies;

use App\Models\User;

/**
 * Authorizes user management actions; admins can manage all users, others only themselves.
 */
class UserPolicy
{
    /**
     * Only admins can list all users.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Admins can view any user; regular users can only view their own profile.
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Admins can update any user; regular users can only update their own profile.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Admins can delete other users, but cannot delete their own account.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
