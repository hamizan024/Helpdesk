<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\WatcherService;
use Illuminate\Http\RedirectResponse;

/**
 * Handles watch/unwatch actions for tickets.
 */
class WatcherController extends Controller
{
    public function __construct(
        private readonly WatcherService $watcherService,
    ) {}

    /**
     * Start watching a ticket.
     */
    public function store(Ticket $ticket): RedirectResponse
    {
        $this->authorize('view', $ticket);

        $this->watcherService->watch($ticket, auth()->user());

        return back()->with('success', 'Kamu sekarang mengikuti tiket ini.');
    }

    /**
     * Stop watching a ticket.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->authorize('view', $ticket);

        $this->watcherService->unwatch($ticket, auth()->user());

        return back()->with('success', 'Kamu berhenti mengikuti tiket ini.');
    }
}