<?php

namespace App\Services;

use App\Enums\ActivityAction;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Encapsulates ticket business logic: retrieval, creation, updates, resolution, and deletion.
 */
class TicketService
{
    public function __construct(
        private readonly ActivityService    $activityService,
        private readonly NotificationService $notificationService,
    ) {}

    /**
     * Return a paginated, filtered list of tickets visible to the given user.
     */
    public function getFiltered(User $user, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->scopeToUser($user);

        if ($search = $filters['search'] ?? null) {
            $query->where(function (Builder $q) use ($search): void {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status = $filters['status'] ?? null) {
            $query->where('status', $status);
        }

        if ($priority = $filters['priority'] ?? null) {
            $query->where('priority', $priority);
        }

        if ($assignedTo = $filters['assigned_to'] ?? null) {
            $query->where('assigned_to', $assignedTo);
        }

        if ($categoryId = $filters['category_id'] ?? null) {
            $query->where('category_id', $categoryId);
        }

        if ($from = $filters['from'] ?? null) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $filters['to'] ?? null) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Return all tickets for the given user (unchanged, used by dashboard).
     */
    public function getForUser(User $user): LengthAwarePaginator
    {
        return $this->scopeToUser($user)->latest()->paginate(20);
    }

    /**
     * Create a new ticket, auto-resolving priority and technician from its category, and log the creation event.
     */
    public function create(User $user, array $data): Ticket
    {
        $category = isset($data['category_id']) ? Category::find($data['category_id']) : null;

        $data['priority']    = $data['priority'] ?? $category?->default_priority ?? 'Medium';
        $data['assigned_to'] = $this->resolveAssignee($category);

        $ticket = Ticket::create(array_merge($data, [
            'ticket_number' => $this->generateTicketNumber(),
            'user_id'       => $user->id,
            'status'        => 'Open',
        ]));

        $this->activityService->log($ticket, $user, ActivityAction::Create, 'Ticket created');

        if ($ticket->assigned_to) {
            $this->activityService->log($ticket, $user, ActivityAction::Assign, 'Auto-assigned based on category department');
            $this->notificationService->notifyAssigned($ticket, $ticket->technician);
        }

        return $ticket;
    }

    /**
     * Pick the technician belonging to the category's department with the fewest active tickets.
     * Returns null when the category has no department, or the department has no technician.
     */
    private function resolveAssignee(?Category $category): ?int
    {
        if (!$category?->department_id) {
            return null;
        }

        return User::where('role', 'technician')
            ->whereRelation('departments', 'departments.id', $category->department_id)
            ->withCount(['assignedTickets' => function (Builder $query): void {
                $query->whereIn('status', ['Open', 'In Progress']);
            }])
            ->orderBy('assigned_tickets_count')
            ->value('id');
    }

    /**
     * Update the ticket, record field-level history, and log high-level events.
     */
    public function update(Ticket $ticket, array $data, User $actor): Ticket
    {
        $previousAssignedTo = $ticket->assigned_to;
        $previousStatus     = $ticket->status;

        $this->recordHistory($ticket, $data, $actor);

        $ticket->update($data);

        if ($previousAssignedTo !== ($data['assigned_to'] ?? $previousAssignedTo)) {
            $this->activityService->log($ticket, $actor, ActivityAction::Assign, 'Assigned technician changed');

            if ($ticket->technician) {
                $this->notificationService->notifyAssigned($ticket, $ticket->technician);
            }
        }

        if ($previousStatus !== ($data['status'] ?? $previousStatus)) {
            $this->activityService->log(
                $ticket,
                $actor,
                ActivityAction::Status,
                "{$previousStatus} → {$data['status']}",
            );
            $this->notificationService->notifyStatusChanged($ticket, $actor);
        }

        return $ticket;
    }

    /**
     * Resolve or close a ticket with optional resolution notes.
     */
    public function resolve(Ticket $ticket, User $actor, ?string $resolutionNotes): Ticket
    {
        $ticket->update([
            'status'           => 'Closed',
            'resolution_notes' => $resolutionNotes,
            'resolved_at'      => now(),
        ]);

        $this->activityService->log(
            $ticket,
            $actor,
            ActivityAction::Resolve,
            'Ticket resolved and closed',
        );

        $this->notificationService->notifyStatusChanged($ticket, $actor);

        return $ticket;
    }

    /**
     * Permanently delete the given ticket.
     */
    public function delete(Ticket $ticket): void
    {
        $ticket->delete();
    }

    private function scopeToUser(User $user): Builder
    {
        $query = Ticket::with(['technician', 'user', 'category']);

        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isTechnician()) {
            return $query->where('assigned_to', $user->id);
        }

        return $query->where('user_id', $user->id);
    }

    private function recordHistory(Ticket $ticket, array $data, User $actor): void
    {
        $tracked = ['title', 'description', 'priority', 'status', 'assigned_to', 'category_id', 'due_date'];

        foreach ($tracked as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }

            $oldValue = (string) ($ticket->getAttribute($field) ?? '');
            $newValue = (string) ($data[$field] ?? '');

            if ($oldValue === $newValue) {
                continue;
            }

            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id'   => $actor->id,
                'field'     => $field,
                'old_value' => $oldValue ?: null,
                'new_value' => $newValue ?: null,
            ]);
        }
    }

    private function generateTicketNumber(): string
    {
        return 'TCK-' . strtoupper(bin2hex(random_bytes(4)));
    }
}