<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Ticket;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;

/**
 * Handles comment and internal note CRUD on tickets.
 */
class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService,
    ) {}

    /**
     * Store a new comment or internal note.
     * Access is restricted to the ticket owner, assigned technician, or admin.
     */
    public function store(StoreCommentRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('create', [Comment::class, $ticket]);

        $isInternal = $request->boolean('is_internal')
            && auth()->user()->isStaff();

        $this->commentService->create(
            $ticket,
            auth()->user(),
            $request->message,
            $isInternal,
        );

        return back()->with('success', $isInternal ? 'Internal note added.' : 'Komentar berhasil ditambahkan.');
    }

    /**
     * Update the message of an existing comment (author or admin only).
     */
    public function update(UpdateCommentRequest $request, Comment $comment): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $comment->user_id !== $user->id) {
            abort(403);
        }

        $this->commentService->update($comment, $request->message);

        return back()->with('success', 'Komentar berhasil diperbarui.');
    }

    /**
     * Delete a comment (author or admin only).
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $comment->user_id !== $user->id) {
            abort(403);
        }

        $this->commentService->delete($comment);

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}