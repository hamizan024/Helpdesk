<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;

class NotificationService
{
    // Notification logic will be implemented in Phase 6.
    // Methods: notifyAssigned(), notifyStatusChanged(), notifyNewComment()

    public function notifyAssigned(Ticket $ticket, User $technician): void
    {
        // Phase 6 implementation
    }

    public function notifyStatusChanged(Ticket $ticket, User $actor): void
    {
        // Phase 6 implementation
    }

    public function notifyNewComment(Ticket $ticket, User $commenter): void
    {
        // Phase 6 implementation
    }
}
