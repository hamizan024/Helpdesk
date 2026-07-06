<?php

namespace App\Services;

use App\Enums\ActivityAction;
use App\Models\Ticket;
use App\Models\TicketWatcher;
use App\Models\User;

/**
 * Manages ticket watchers (users who subscribe to ticket updates).
 */
class WatcherService
{
    public function __construct(
        private readonly ActivityService $activityService,
    ) {}

    public function watch(Ticket $ticket, User $user): void
    {
        if ($this->isWatching($ticket, $user)) {
            return;
        }

        TicketWatcher::create(['ticket_id' => $ticket->id, 'user_id' => $user->id]);

        $this->activityService->log(
            $ticket,
            $user,
            ActivityAction::Watch,
            "{$user->name} started watching this ticket",
        );
    }

    public function unwatch(Ticket $ticket, User $user): void
    {
        TicketWatcher::where('ticket_id', $ticket->id)
            ->where('user_id', $user->id)
            ->delete();
    }

    public function isWatching(Ticket $ticket, User $user): bool
    {
        return TicketWatcher::where('ticket_id', $ticket->id)
            ->where('user_id', $user->id)
            ->exists();
    }
}