<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Models\Ticket;
use App\Services\TicketService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Manages ticket lifecycle: listing, creation, viewing, editing, and deletion.
 */
class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
        private readonly UserService $userService,
    ) {}

    /**
     * Display a paginated list of tickets scoped to the current user's role.
     */
    public function index(): View
    {
        $tickets = $this->ticketService->getForUser(auth()->user());

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the ticket creation form.
     */
    public function create(): View
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created ticket.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $this->ticketService->create(auth()->user(), $request->validated());

        return redirect()->route('tickets.index')
            ->with('success', 'Tiket berhasil dibuat.');
    }

    /**
     * Display a single ticket with its comments and activity timeline.
     */
    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->loadMissing(['comments.user', 'activities.user', 'technician', 'user']);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the ticket edit form. Admin only.
     */
    public function edit(Ticket $ticket): View
    {
        $this->authorize('update', $ticket);

        $technicians = $this->userService->getTechnicians();

        return view('tickets.edit', compact('ticket', 'technicians'));
    }

    /**
     * Update the specified ticket. Admin only.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $this->ticketService->update($ticket, $request->validated(), auth()->user());

        return redirect()->route('tickets.index')
            ->with('success', 'Tiket berhasil diperbarui.');
    }

    /**
     * Delete the specified ticket. Admin only.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->authorize('delete', $ticket);

        $this->ticketService->delete($ticket);

        return redirect()->route('tickets.index')
            ->with('success', 'Tiket berhasil dihapus.');
    }
}
