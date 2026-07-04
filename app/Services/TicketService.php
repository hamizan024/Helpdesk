<?php

namespace App\Services;

use App\Enums\ActivityAction;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Encapsulates ticket business logic: retrieval, creation, updates, and deletion.
 */
class TicketService
{
    public function __construct(
        private readonly ActivityService $activityService,
    ) {}

    /**
     * Return a paginated list of tickets visible to the given user.
     *
     * Admins see all tickets; technicians see only their assigned tickets;
     * regular users see only their own submissions.
     */
    public function getForUser(User $user): LengthAwarePaginator
    {
        if ($user->isAdmin()) {
            return Ticket::with('technician')->latest()->paginate(20);
        }

        if ($user->isTechnician()) {
            return Ticket::with('technician')
                ->where('assigned_to', $user->id)
                ->latest()
                ->paginate(20);
        }

        return Ticket::with('technician')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);
    }

    /**
     * Create a new ticket submitted by the given user and log the creation event.
     */
    public function create(User $user, array $data): Ticket
    {
        $ticket = Ticket::create(array_merge($data, [
            'ticket_number' => $this->generateTicketNumber(),
            'user_id'       => $user->id,
            'status'        => 'Open',
        ]));

        $this->activityService->log($ticket, $user, ActivityAction::Create, 'Ticket created');

        return $ticket;
    }

    /**
     * Update the ticket with new data and log assignment or status changes.
     */
    public function update(Ticket $ticket, array $data, User $actor): Ticket
    {
        $previousAssignedTo = $ticket->assigned_to;
        $previousStatus     = $ticket->status;

        $ticket->update($data);

        if ($previousAssignedTo != ($data['assigned_to'] ?? null)) {
            $this->activityService->log($ticket, $actor, ActivityAction::Assign, 'Assigned technician changed');
        }

        if ($previousStatus != $data['status']) {
            $this->activityService->log(
                $ticket,
                $actor,
                ActivityAction::Status,
                "{$previousStatus} -> {$data['status']}",
            );
        }

        return $ticket;
    }

    /**
     * Permanently delete the given ticket.
     */
    public function delete(Ticket $ticket): void
    {
        $ticket->delete();
    }

    /**
     * Generate a collision-safe unique ticket number.
     */
    private function generateTicketNumber(): string
    {
        return 'TCK-' . strtoupper(bin2hex(random_bytes(4)));
    }
}
