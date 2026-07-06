<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\ResolveTicketRequest;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use App\Services\AttachmentService;
use App\Services\TicketExportService;
use App\Services\TicketService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Manages the full ticket lifecycle including filtering, export, resolution, and print.
 */
class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService      $ticketService,
        private readonly UserService        $userService,
        private readonly AttachmentService  $attachmentService,
        private readonly TicketExportService $exportService,
    ) {}

    /**
     * Display a filtered, paginated list of tickets.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status', 'priority', 'assigned_to', 'category_id', 'from', 'to']);
        $tickets = $this->ticketService->getFiltered(auth()->user(), $filters);

        $categories  = Category::where('is_active', true)->orderBy('name')->get();
        $technicians = $this->userService->getTechnicians();

        return view('tickets.index', compact('tickets', 'filters', 'categories', 'technicians'));
    }

    /**
     * Show the ticket creation form.
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('tickets.create', compact('categories'));
    }

    /**
     * Store a newly created ticket and optional attachments.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $ticket = $this->ticketService->create(auth()->user(), $request->safe()->except('attachments'));

        foreach ($request->file('attachments', []) as $file) {
            $this->attachmentService->store($ticket, auth()->user(), $file);
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Tiket berhasil dibuat.');
    }

    /**
     * Display a single ticket with comments, attachments, watchers, and history.
     */
    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $user = auth()->user();

        $ticket->loadMissing([
            'user',
            'technician',
            'category',
            'attachments.user',
            'watchers.user',
            'histories.user',
            'activities.user',
        ]);

        $comments = $ticket->comments()
            ->with('user')
            ->when(!$user->isStaff(), fn ($q) => $q->where('is_internal', false))
            ->latest()
            ->get();

        $isWatching  = $ticket->watchers->where('user_id', $user->id)->isNotEmpty();
        $technicians = $user->isAdmin() ? $this->userService->getTechnicians() : collect();
        $categories  = $user->isAdmin() ? Category::where('is_active', true)->orderBy('name')->get() : collect();

        return view('tickets.show', compact(
            'ticket', 'comments', 'isWatching', 'technicians', 'categories',
        ));
    }

    /**
     * Show the ticket edit form (admin only).
     */
    public function edit(Ticket $ticket): View
    {
        $this->authorize('update', $ticket);

        $technicians = $this->userService->getTechnicians();
        $categories  = Category::where('is_active', true)->orderBy('name')->get();

        return view('tickets.edit', compact('ticket', 'technicians', 'categories'));
    }

    /**
     * Update the ticket (admin only).
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $this->ticketService->update($ticket, $request->validated(), auth()->user());

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Tiket berhasil diperbarui.');
    }

    /**
     * Resolve (close) a ticket with optional resolution notes.
     */
    public function resolve(ResolveTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $this->ticketService->resolve($ticket, auth()->user(), $request->resolution_notes);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Tiket berhasil diselesaikan.');
    }

    /**
     * Delete the ticket (admin only).
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->authorize('delete', $ticket);

        $this->ticketService->delete($ticket);

        return redirect()->route('tickets.index')
            ->with('success', 'Tiket berhasil dihapus.');
    }

    /**
     * Stream a CSV export of the filtered ticket list.
     */
    public function export(Request $request): StreamedResponse
    {
        $filters  = $request->only(['search', 'status', 'priority', 'assigned_to', 'category_id', 'from', 'to']);
        $user     = auth()->user();
        $filename = 'tickets-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(
            fn () => $this->exportService->streamCsv($user, $filters),
            $filename,
            ['Content-Type' => 'text/csv; charset=UTF-8'],
        );
    }

    /**
     * Return a print-optimised view of a single ticket.
     */
    public function print(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->loadMissing([
            'user', 'technician', 'category',
            'attachments.user',
            'activities.user',
        ]);

        $comments = $ticket->comments()
            ->with('user')
            ->when(!auth()->user()->isStaff(), fn ($q) => $q->where('is_internal', false))
            ->latest()
            ->get();

        return view('tickets.print', compact('ticket', 'comments'));
    }
}