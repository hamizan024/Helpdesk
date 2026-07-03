<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class CommentPolicy
{
    /**
     * Mirrors ticket visibility: admin, ticket owner, or assigned technician can comment.
     * Called via: $this->authorize('create', [Comment::class, $ticket])
     */
    public function create(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin()
            || $ticket->user_id === $user->id
            || $ticket->assigned_to === $user->id;
    }
}
