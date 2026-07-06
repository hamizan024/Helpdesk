<?php

namespace App\Services;

use App\Enums\ActivityAction;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Manages file attachments on tickets.
 */
class AttachmentService
{
    public function __construct(
        private readonly ActivityService $activityService,
    ) {}

    /**
     * Store a file and create the attachment record.
     */
    public function store(Ticket $ticket, User $user, UploadedFile $file): TicketAttachment
    {
        $path = $file->store("attachments/{$ticket->id}", 'public');

        $attachment = TicketAttachment::create([
            'ticket_id'     => $ticket->id,
            'user_id'       => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'path'          => $path,
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
        ]);

        $this->activityService->log(
            $ticket,
            $user,
            ActivityAction::Attachment,
            "File attached: {$file->getClientOriginalName()}",
        );

        return $attachment;
    }

    /**
     * Delete a file from storage and remove its record.
     */
    public function delete(TicketAttachment $attachment): void
    {
        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();
    }
}