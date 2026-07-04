<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

/**
 * Authorizes comment actions based on the user's relationship to the ticket.
 */
class CommentPolicy
{
    /**
     * The ticket owner, assigned technician, or an admin can comment.
     *
     * Called via: $this->authorize('create', [Comment::class, $ticket])
     */
    public function create(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin()
            || $ticket->user_id === $user->id
            || $ticket->assigned_to === $user->id;
    }
}
