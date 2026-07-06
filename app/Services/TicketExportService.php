<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Generates CSV exports of ticket lists.
 */
class TicketExportService
{
    /**
     * Write CSV rows to the current output stream (php://output).
     * Call inside response()->streamDownload() for proper HTTP response handling.
     */
    public function streamCsv(User $user, array $filters): void
    {
        $query  = $this->buildQuery($user, $filters);
        $handle = fopen('php://output', 'w');

        // UTF-8 BOM so Excel auto-detects encoding
        fprintf($handle, "\xEF\xBB\xBF");

        fputcsv($handle, [
            'Ticket Number', 'Title', 'Priority', 'Status',
            'Reporter', 'Assigned To', 'Category', 'Due Date',
            'Created At', 'Resolved At',
        ]);

        $query->with(['user', 'technician', 'category'])->lazyById()->each(function ($ticket) use ($handle): void {
            fputcsv($handle, [
                $ticket->ticket_number,
                $ticket->title,
                $ticket->priority,
                $ticket->status,
                $ticket->user->name ?? '-',
                $ticket->technician->name ?? '-',
                $ticket->category->name ?? '-',
                $ticket->due_date?->format('d/m/Y') ?? '-',
                $ticket->created_at->format('d/m/Y H:i'),
                $ticket->resolved_at?->format('d/m/Y H:i') ?? '-',
            ]);
        });

        fclose($handle);
    }

    private function buildQuery(User $user, array $filters): Builder
    {
        $query = \App\Models\Ticket::query();

        if (!$user->isAdmin()) {
            if ($user->isTechnician()) {
                $query->where('assigned_to', $user->id);
            } else {
                $query->where('user_id', $user->id);
            }
        }

        if ($search = $filters['search'] ?? null) {
            $query->where(function (Builder $q) use ($search): void {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
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

        if ($from = $filters['from'] ?? null) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $filters['to'] ?? null) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query->latest();
    }
}