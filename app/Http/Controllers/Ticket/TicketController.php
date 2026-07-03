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

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
        private readonly UserService $userService
    ) {}

    public function index(): View
    {
        $tickets = $this->ticketService->getForUser(auth()->user());

        return view('tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('tickets.create');
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $this->ticketService->create(auth()->user(), $request->validated());

        return redirect()->route('tickets.index')
                         ->with('success', 'Tiket berhasil dibuat.');
    }

    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->loadMissing(['comments.user', 'activities.user', 'technician', 'user']);

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket): View
    {
        $this->authorize('update', $ticket);

        $technicians = $this->userService->getTechnicians();

        return view('tickets.edit', compact('ticket', 'technicians'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $this->ticketService->update($ticket, $request->validated(), auth()->user());

        return redirect()->route('tickets.index')
                         ->with('success', 'Tiket berhasil diperbarui.');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->authorize('delete', $ticket);

        $this->ticketService->delete($ticket);

        return redirect()->route('tickets.index')
                         ->with('success', 'Tiket berhasil dihapus.');
    }
}
