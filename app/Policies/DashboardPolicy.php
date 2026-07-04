<?php

namespace App\Policies;

use App\Models\User;

/**
 * Authorizes access to the dashboard.
 *
 * Registered as a named Gate: Gate::define('view-dashboard', ...)
 */
class DashboardPolicy
{
    /**
     * All authenticated users can access the dashboard.
     */
    public function view(User $user): bool
    {
        return true;
    }
}
