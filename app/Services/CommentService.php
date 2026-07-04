<?php

namespace App\Services;

use App\Enums\ActivityAction;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;

/**
 * Handles comment creation and the associated activity logging.
 */
class CommentService
{
    public function __construct(
        private readonly ActivityService $activityService,
    ) {}

    /**
     * Add a comment to the ticket and log the activity.
     */
    public function create(Ticket $ticket, User $user, string $message): Comment
    {
        $comment = Comment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'message'   => $message,
        ]);

        $this->activityService->log($ticket, $user, ActivityAction::Comment, 'Comment added');

        return $comment;
    }
}
