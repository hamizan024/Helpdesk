<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function getStats(User $user): array
    {
        $q = $this->baseQuery($user);

        return [
            'totalTickets'      => (clone $q)->count(),
            'openTickets'       => (clone $q)->where('status', 'Open')->count(),
            'inProgressTickets' => (clone $q)->where('status', 'In Progress')->count(),
            'closedTickets'     => (clone $q)->where('status', 'Closed')->count(),
            'highPriority'      => (clone $q)->where('priority', 'High')->count(),
            'mediumPriority'    => (clone $q)->where('priority', 'Medium')->count(),
            'lowPriority'       => (clone $q)->where('priority', 'Low')->count(),
        ];
    }

    public function getRecentTickets(User $user, int $limit = 5): Collection
    {
        return $this->baseQuery($user)
            ->with('technician')
            ->latest()
            ->take($limit)
            ->get();
    }

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
