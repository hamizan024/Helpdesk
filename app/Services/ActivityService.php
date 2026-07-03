<?php

namespace App\Services;

use App\Enums\ActivityAction;
use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\User;

class ActivityService
{
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
