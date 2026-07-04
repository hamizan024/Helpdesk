<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Provides role-aware dashboard statistics and recent ticket data.
 */
class DashboardService
{
    /**
     * Return ticket counts grouped by status and priority for the given user.
     *
     * @return array{
     *     totalTickets: int,
     *     openTickets: int,
     *     inProgressTickets: int,
     *     closedTickets: int,
     *     highPriority: int,
     *     mediumPriority: int,
     *     lowPriority: int,
     * }
     */
    public function getStats(User $user): array
    {
        $query = $this->baseQuery($user);

        return [
            'totalTickets'      => (clone $query)->count(),
            'openTickets'       => (clone $query)->where('status', 'Open')->count(),
            'inProgressTickets' => (clone $query)->where('status', 'In Progress')->count(),
            'closedTickets'     => (clone $query)->where('status', 'Closed')->count(),
            'highPriority'      => (clone $query)->where('priority', 'High')->count(),
            'mediumPriority'    => (clone $query)->where('priority', 'Medium')->count(),
            'lowPriority'       => (clone $query)->where('priority', 'Low')->count(),
        ];
    }

    /**
     * Return the most recent tickets visible to the given user.
     */
    public function getRecentTickets(User $user, int $limit = 5): Collection
    {
        return $this->baseQuery($user)
            ->with('technician')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Build a base query scoped to tickets the given user is allowed to see.
     */
    private function baseQuery(User $user): Builder
    {
        if ($user->isAdmin()) {
            return Ticket::query();
        }

        if ($user->isTechnician()) {
            return Ticket::where('assigned_to', $user->id);
        }

        return Ticket::where('user_id', $user->id);
    }
}
