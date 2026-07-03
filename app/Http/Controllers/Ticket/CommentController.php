<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Ticket;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService
    ) {}

    public function store(StoreCommentRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('create', [Comment::class, $ticket]);

        $this->commentService->create($ticket, auth()->user(), $request->message);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
