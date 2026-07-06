<?php

namespace App\Services;

use App\Enums\ActivityAction;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;

/**
 * Handles comment and internal note creation, editing, and deletion.
 */
class CommentService
{
    public function __construct(
        private readonly ActivityService    $activityService,
        private readonly NotificationService $notificationService,
    ) {}

    /**
     * Add a public comment or internal note to the ticket.
     */
    public function create(Ticket $ticket, User $user, string $message, bool $isInternal = false): Comment
    {
        $comment = Comment::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => $user->id,
            'message'     => $message,
            'is_internal' => $isInternal,
        ]);

        $action = $isInternal ? ActivityAction::Note : ActivityAction::Comment;
        $label  = $isInternal ? 'Internal note added' : 'Comment added';

        $this->activityService->log($ticket, $user, $action, $label);

        if (!$isInternal) {
            $this->notificationService->notifyNewComment($ticket, $user);
        }

        return $comment;
    }

    /**
     * Update the message of an existing comment (author or admin only).
     */
    public function update(Comment $comment, string $message): Comment
    {
        $comment->update(['message' => $message]);

        return $comment;
    }

    /**
     * Delete a comment (author or admin only).
     */
    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}