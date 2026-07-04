<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Provides user-related queries used across the application.
 */
class UserService
{
    /**
     * Return all users with the technician role.
     */
    public function getTechnicians(): Collection
    {
        return User::where('role', 'technician')->get();
    }
}
