<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

/**
 * Authorizes ticket actions based on the user's role and ticket ownership.
 */
class TicketPolicy
{
    /**
     * All authenticated users can list tickets (scope is enforced in the service layer).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Admins, the ticket owner, and the assigned technician can view a ticket.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin()
            || $ticket->user_id === $user->id
            || $ticket->assigned_to === $user->id;
    }

    /**
     * All authenticated users can create tickets.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only admins can update tickets.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can delete tickets.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin();
    }
}
