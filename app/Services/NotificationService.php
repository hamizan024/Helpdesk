<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;

/**
 * Stub for outbound notifications — full implementation deferred to Phase 6.
 */
class NotificationService
{
    /**
     * Notify the assigned technician when a ticket is assigned to them.
     */
    public function notifyAssigned(Ticket $ticket, User $technician): void
    {
    }

    /**
     * Notify relevant parties when a ticket's status changes.
     */
    public function notifyStatusChanged(Ticket $ticket, User $actor): void
    {
    }

    /**
     * Notify the ticket owner and assigned technician when a comment is added.
     */
    public function notifyNewComment(Ticket $ticket, User $commenter): void
    {
    }
}
