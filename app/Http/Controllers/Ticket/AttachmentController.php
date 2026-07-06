<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\StoreAttachmentRequest;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Services\AttachmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Handles file attachment upload, download, and deletion on tickets.
 */
class AttachmentController extends Controller
{
    public function __construct(
        private readonly AttachmentService $attachmentService,
    ) {}

    /**
     * Upload a file and attach it to the ticket.
     */
    public function store(StoreAttachmentRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('view', $ticket);

        $this->attachmentService->store($ticket, auth()->user(), $request->file('file'));

        return back()->with('success', 'File berhasil diupload.');
    }

    /**
     * Stream-download an attachment.
     */
    public function download(Ticket $ticket, TicketAttachment $attachment): StreamedResponse
    {
        $this->authorize('view', $ticket);

        abort_if($attachment->ticket_id !== $ticket->id, 404);

        return Storage::disk('public')->download($attachment->path, $attachment->original_name);
    }

    /**
     * Delete an attachment (uploader or admin only).
     */
    public function destroy(Ticket $ticket, TicketAttachment $attachment): RedirectResponse
    {
        abort_if($attachment->ticket_id !== $ticket->id, 404);

        $user = auth()->user();

        if (!$user->isAdmin() && $attachment->user_id !== $user->id) {
            abort(403);
        }

        $this->attachmentService->delete($attachment);

        return back()->with('success', 'Attachment berhasil dihapus.');
    }
}