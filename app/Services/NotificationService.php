<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\CommentAddedNotification;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketStatusChangedNotification;
use Illuminate\Support\Collection;

/**
 * Dispatches in-app (database) notifications for ticket lifecycle events.
 */
class NotificationService
{
    /**
     * Notify the assigned technician that a ticket has been assigned to them.
     */
    public function notifyAssigned(Ticket $ticket, ?User $technician): void
    {
        $technician?->notify(new TicketAssignedNotification($ticket));
    }

    /**
     * Notify the ticket creator and all watchers of a status change.
     */
    public function notifyStatusChanged(Ticket $ticket, User $actor): void
    {
        foreach ($this->getRecipients($ticket, $actor) as $recipient) {
            $recipient->notify(new TicketStatusChangedNotification($ticket, $actor));
        }
    }

    /**
     * Notify the ticket creator and watchers of a new comment.
     */
    public function notifyNewComment(Ticket $ticket, User $commenter): void
    {
        foreach ($this->getRecipients($ticket, $commenter) as $recipient) {
            $recipient->notify(new CommentAddedNotification($ticket, $commenter));
        }
    }

    /**
     * Build the unique set of recipients for a ticket event, excluding the actor.
     */
    private function getRecipients(Ticket $ticket, User $exclude): Collection
    {
        $ticket->loadMissing('watchers.user');

        $ids = collect([$ticket->user_id]);
        $ids = $ids->merge($ticket->watchers->pluck('user_id'));

        return User::whereIn('id', $ids->unique())
            ->where('id', '!=', $exclude->id)
            ->get();
    }
}