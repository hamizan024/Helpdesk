<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * REST API for ticket operations — authenticated via Sanctum personal access tokens.
 */
class TicketApiController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
    ) {}

    /**
     * @api GET /api/v1/tickets
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'status', 'priority', 'assigned_to', 'category_id', 'from', 'to']);
        $tickets = $this->ticketService->getFiltered(
            $request->user(),
            $filters,
            $request->integer('per_page', 15),
        );

        return response()->json([
            'success' => true,
            'data'    => $tickets->items(),
            'meta'    => [
                'total'        => $tickets->total(),
                'per_page'     => $tickets->perPage(),
                'current_page' => $tickets->currentPage(),
                'last_page'    => $tickets->lastPage(),
            ],
        ]);
    }

    /**
     * @api POST /api/v1/tickets
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority'    => ['nullable', 'in:Low,Medium,High'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'due_date'    => ['nullable', 'date'],
        ]);

        $ticket = $this->ticketService->create($request->user(), $data);

        return response()->json([
            'success' => true,
            'data'    => $ticket->fresh(['user', 'technician', 'category']),
            'message' => 'Ticket created successfully.',
        ], 201);
    }

    /**
     * @api GET /api/v1/tickets/{ticket}
     */
    public function show(Ticket $ticket, Request $request): JsonResponse
    {
        $this->authorize('view', $ticket);

        $ticket->loadMissing(['user', 'technician', 'category', 'attachments', 'activities.user']);

        $comments = $ticket->comments()
            ->with('user')
            ->when(!$request->user()->isStaff(), fn ($q) => $q->where('is_internal', false))
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => array_merge($ticket->toArray(), ['comments' => $comments]),
        ]);
    }

    /**
     * @api PATCH /api/v1/tickets/{ticket}
     */
    public function update(Request $request, Ticket $ticket): JsonResponse
    {
        $this->authorize('update', $ticket);

        $data = $request->validate([
            'title'       => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'priority'    => ['sometimes', 'required', 'in:Low,Medium,High'],
            'status'      => ['sometimes', 'required', 'in:Open,In Progress,Closed'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'due_date'    => ['nullable', 'date'],
        ]);

        $ticket = $this->ticketService->update($ticket, $data, $request->user());

        return response()->json([
            'success' => true,
            'data'    => $ticket->fresh(['user', 'technician', 'category']),
            'message' => 'Ticket updated successfully.',
        ]);
    }

    /**
     * @api DELETE /api/v1/tickets/{ticket}
     */
    public function destroy(Ticket $ticket): JsonResponse
    {
        $this->authorize('delete', $ticket);

        $this->ticketService->delete($ticket);

        return response()->json([
            'success' => true,
            'message' => 'Ticket deleted successfully.',
        ]);
    }
}