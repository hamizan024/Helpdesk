<?php

namespace App\Services;

use App\Enums\ActivityAction;
use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\User;

/**
 * Records audit trail entries for ticket lifecycle events.
 */
class ActivityService
{
    /**
     * Write an activity log entry for the given ticket event.
     */
    public function log(Ticket $ticket, User $user, ActivityAction $action, string $description): void
    {
        ActivityLog::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => $user->id,
            'action'      => $action->value,
            'description' => $description,
        ]);
    }
}
