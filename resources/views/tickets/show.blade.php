@extends('layouts.app')

@section('title', 'Ticket — ' . $ticket->ticket_number)

@section('content')

@php
    $user       = auth()->user();
    $isStaff    = $user->isStaff();
    $isAdmin    = $user->isAdmin();
    $statusGrad = match($ticket->status) {
        'Open'        => 'linear-gradient(195deg,#42424a,#191919)',
        'In Progress' => 'linear-gradient(195deg,#49a3f1,#1A73E8)',
        'Closed'      => 'linear-gradient(195deg,#66BB6A,#43A047)',
        default       => 'linear-gradient(195deg,#42424a,#191919)',
    };
    $actionConfig = [
        'create'     => ['bg' => 'linear-gradient(195deg,#66BB6A,#43A047)', 'icon' => 'add_circle'],
        'assign'     => ['bg' => 'linear-gradient(195deg,#49a3f1,#1A73E8)', 'icon' => 'person_add'],
        'status'     => ['bg' => 'linear-gradient(195deg,#FFA726,#FB8C00)', 'icon' => 'swap_horiz'],
        'comment'    => ['bg' => 'linear-gradient(195deg,#EC407A,#D81B60)', 'icon' => 'chat'],
        'resolve'    => ['bg' => 'linear-gradient(195deg,#26C6DA,#00ACC1)', 'icon' => 'check_circle'],
        'attachment' => ['bg' => 'linear-gradient(195deg,#78909C,#546E7A)', 'icon' => 'attach_file'],
        'watch'      => ['bg' => 'linear-gradient(195deg,#26A69A,#00897B)', 'icon' => 'visibility'],
        'note'       => ['bg' => 'linear-gradient(195deg,#FFA726,#FB8C00)', 'icon' => 'lock'],
    ];
@endphp

@if(session('success'))
    <x-alert type="success">{{ session('success') }}</x-alert>
@endif

{{-- Page header --}}
<div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h5 class="mb-0 fw-semibold" style="color:#344767;">{{ $ticket->title }}</h5>
        <span class="text-muted" style="font-size:0.78rem;">{{ $ticket->ticket_number }}</span>
    </div>
    <div class="d-flex gap-2 flex-wrap align-items-center">
        <span class="badge text-white" style="background:{{ $statusGrad }};font-size:0.8rem;padding:6px 14px;">
            {{ $ticket->status }}
        </span>
        <a href="{{ route('tickets.print', $ticket->id) }}" target="_blank"
           class="btn btn-sm btn-outline-secondary">
            <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">print</span>Print
        </a>
        <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-outline-secondary">
            <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">arrow_back</span>Back
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- ===== LEFT COLUMN ===== --}}
    <div class="col-lg-8">

        {{-- Description --}}
        <x-app-card title="Description" icon="description" class="mb-4">
            <p style="line-height:1.7;color:#344767;font-size:0.875rem;white-space:pre-wrap;margin:0;">{{ $ticket->description }}</p>
            @if($ticket->resolution_notes)
                <div class="mt-3 p-3 rounded-3" style="background:linear-gradient(135deg,#e8f5e9,#f1f8e9);border-left:4px solid #66BB6A;">
                    <div class="fw-semibold mb-1" style="font-size:0.78rem;color:#2e7d32;text-transform:uppercase;letter-spacing:.05em;">
                        <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">check_circle</span>
                        Resolution Notes
                    </div>
                    <p class="mb-0" style="font-size:0.875rem;color:#344767;line-height:1.6;">{{ $ticket->resolution_notes }}</p>
                    @if($ticket->resolved_at)
                        <div class="mt-1 text-muted" style="font-size:0.72rem;">Resolved {{ $ticket->resolved_at->diffForHumans() }}</div>
                    @endif
                </div>
            @endif
        </x-app-card>

        {{-- Attachments --}}
        <x-app-card title="Attachments" icon="attach_file" :badge="$ticket->attachments->count() ?: null" class="mb-4">
            @if($ticket->attachments->count())
                <div class="row g-2 mb-3">
                    @foreach($ticket->attachments as $att)
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded-2 border" style="background:#fafafa;">
                            <span class="material-icons-round" style="font-size:1.5rem;color:#7b809a;">{{ $att->getIconName() }}</span>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-truncate" style="font-size:0.8rem;color:#344767;">{{ $att->original_name }}</div>
                                <div class="text-muted" style="font-size:0.7rem;">{{ $att->getSizeFormatted() }} · {{ $att->created_at->format('d M') }}</div>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('tickets.attachments.download', [$ticket->id, $att->id]) }}"
                                   class="btn btn-link btn-sm p-1 text-primary" title="Download">
                                    <span class="material-icons-round" style="font-size:1rem;">download</span>
                                </a>
                                @if($isAdmin || $att->user_id === $user->id)
                                    <form action="{{ route('tickets.attachments.destroy', [$ticket->id, $att->id]) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link btn-sm p-1 text-danger"
                                                onclick="return confirm('Delete this file?')">
                                            <span class="material-icons-round" style="font-size:1rem;">delete</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('tickets.attachments.store', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="d-flex gap-2 align-items-start">
                    <div class="flex-grow-1">
                        <input type="file" name="file"
                               class="form-control form-control-sm @error('file') is-invalid @enderror"
                               accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt">
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-primary" style="white-space:nowrap;">
                        <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">upload</span>Upload
                    </button>
                </div>
            </form>
        </x-app-card>

        {{-- Tabbed: Comments | Internal Notes | Changes | Activity --}}
        <div class="mb-4">
            <ul class="nav" id="showTabs" style="border-bottom:2px solid #f0f2f5;">
                <li class="nav-item">
                    <button class="show-tab-btn active" data-target="tabComments">
                        <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;margin-right:4px;">forum</span>
                        Comments
                        <span class="tab-badge">{{ $comments->where('is_internal', false)->count() }}</span>
                    </button>
                </li>
                @if($isStaff)
                <li class="nav-item">
                    <button class="show-tab-btn" data-target="tabNotes">
                        <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;margin-right:4px;">lock</span>
                        Internal
                        <span class="tab-badge" style="background:#fff3e0;color:#e65100;">{{ $comments->where('is_internal', true)->count() }}</span>
                    </button>
                </li>
                @endif
                <li class="nav-item">
                    <button class="show-tab-btn" data-target="tabHistory">
                        <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;margin-right:4px;">manage_history</span>
                        Changes
                        <span class="tab-badge">{{ $ticket->histories->count() }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="show-tab-btn" data-target="tabActivity">
                        <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;margin-right:4px;">timeline</span>
                        Activity
                    </button>
                </li>
            </ul>

            <style>
                .show-tab-btn {
                    background: none; border: none;
                    border-bottom: 2px solid transparent; margin-bottom: -2px;
                    padding: 10px 16px; font-size: 0.82rem; font-weight: 600;
                    color: #7b809a; cursor: pointer; display: flex; align-items: center; gap: 4px;
                }
                .show-tab-btn.active { border-bottom-color: #EC407A; color: #EC407A; }
                .show-tab-pane { display: none; }
                .show-tab-pane.active { display: block; }
                .tab-badge {
                    display:inline-block;background:#f0f2f5;color:#344767;
                    font-size:0.65rem;padding:2px 6px;border-radius:999px;margin-left:4px;
                }
            </style>

            <div style="background:#fff;border:1px solid #f0f2f5;border-top:none;border-radius:0 0 12px 12px;padding:20px;">

                {{-- PANE: Comments --}}
                <div class="show-tab-pane active" id="tabComments">
                    @forelse($comments->where('is_internal', false) as $comment)
                        <div class="d-flex gap-3 mb-4" id="comment-{{ $comment->id }}">
                            <div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;
                                        background:linear-gradient(195deg,#EC407A,#D81B60);
                                        display:flex;align-items:center;justify-content:center;
                                        color:white;font-size:0.875rem;font-weight:600;">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-semibold" style="font-size:0.85rem;color:#344767;">{{ $comment->user->name }}</span>
                                    <span class="text-muted" style="font-size:0.72rem;">{{ $comment->created_at->diffForHumans() }}</span>
                                    @if($isAdmin || $comment->user_id === $user->id)
                                        <span class="ms-auto d-flex gap-2">
                                            <button class="btn btn-link btn-sm p-0 text-muted" style="font-size:0.72rem;"
                                                    onclick="toggleEdit({{ $comment->id }})">Edit</button>
                                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link btn-sm p-0 text-danger" style="font-size:0.72rem;"
                                                        onclick="return confirm('Delete comment?')">Delete</button>
                                            </form>
                                        </span>
                                    @endif
                                </div>
                                <div class="comment-body" style="background:#f8f9fa;border-radius:0 10px 10px 10px;padding:10px 14px;font-size:0.85rem;color:#344767;line-height:1.6;">{{ $comment->message }}</div>
                                <form class="comment-edit d-none mt-2" action="{{ route('comments.update', $comment->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <textarea name="message" class="form-control form-control-sm mb-2" rows="2">{{ $comment->message }}</textarea>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="toggleEdit({{ $comment->id }})">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <span class="material-icons-round d-block mb-1" style="font-size:2rem;opacity:0.2;">chat_bubble_outline</span>
                            <span style="font-size:0.82rem;">No comments yet.</span>
                        </div>
                    @endforelse

                    <hr style="border-color:#f0f2f5;">
                    <form action="{{ route('comments.store', $ticket->id) }}" method="POST">
                        @csrf
                        <x-form-textarea label="Add Comment" name="message" :rows="3"
                                         :value="old('message')" placeholder="Write a comment…" />
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">send</span>Send
                        </button>
                    </form>
                </div>

                {{-- PANE: Internal Notes --}}
                @if($isStaff)
                <div class="show-tab-pane" id="tabNotes">
                    @forelse($comments->where('is_internal', true) as $note)
                        <div class="d-flex gap-3 mb-4">
                            <div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;
                                        background:linear-gradient(195deg,#FFA726,#FB8C00);
                                        display:flex;align-items:center;justify-content:center;
                                        color:white;font-size:0.875rem;font-weight:600;">
                                {{ strtoupper(substr($note->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-semibold" style="font-size:0.85rem;color:#344767;">{{ $note->user->name }}</span>
                                    <span class="badge" style="background:#fff3e0;color:#e65100;font-size:0.65rem;padding:2px 6px;">
                                        <span class="material-icons-round" style="font-size:0.7rem;vertical-align:middle;">lock</span> Internal
                                    </span>
                                    <span class="text-muted" style="font-size:0.72rem;">{{ $note->created_at->diffForHumans() }}</span>
                                    @if($isAdmin || $note->user_id === $user->id)
                                        <span class="ms-auto">
                                            <form action="{{ route('comments.destroy', $note->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link btn-sm p-0 text-danger" style="font-size:0.72rem;"
                                                        onclick="return confirm('Delete note?')">Delete</button>
                                            </form>
                                        </span>
                                    @endif
                                </div>
                                <div style="background:#fff8e1;border-left:3px solid #FFA726;border-radius:0 10px 10px 10px;padding:10px 14px;font-size:0.85rem;color:#344767;line-height:1.6;">{{ $note->message }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <span class="material-icons-round d-block mb-1" style="font-size:2rem;opacity:0.2;">lock_outline</span>
                            <span style="font-size:0.82rem;">No internal notes yet.</span>
                        </div>
                    @endforelse

                    <hr style="border-color:#f0f2f5;">
                    <form action="{{ route('comments.store', $ticket->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_internal" value="1">
                        <x-form-textarea label="Add Internal Note" name="message" :rows="3"
                                         placeholder="Visible to staff only…" />
                        <button type="submit" class="btn btn-warning btn-sm px-4 text-white">
                            <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">lock</span>Add Note
                        </button>
                    </form>
                </div>
                @endif

                {{-- PANE: Field Changes --}}
                <div class="show-tab-pane" id="tabHistory">
                    @forelse($ticket->histories->sortByDesc('created_at') as $history)
                        <div class="d-flex gap-3 mb-3 pb-3" style="border-bottom:1px solid #f0f2f5;">
                            <div style="width:32px;height:32px;border-radius:50%;flex-shrink:0;
                                        background:linear-gradient(195deg,#42424a,#191919);
                                        display:flex;align-items:center;justify-content:center;">
                                <span class="material-icons-round text-white" style="font-size:0.85rem;">edit</span>
                            </div>
                            <div class="flex-grow-1">
                                <div style="font-size:0.82rem;color:#344767;">
                                    <span class="fw-semibold">{{ $history->user->name }}</span>
                                    changed <span class="fw-semibold">{{ $history->getFieldLabel() }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                    @if($history->old_value)
                                        <span class="badge" style="background:#fdecea;color:#c62828;font-size:0.7rem;">{{ Str::limit($history->old_value, 40) }}</span>
                                        <span class="material-icons-round text-muted" style="font-size:0.9rem;">arrow_forward</span>
                                    @endif
                                    @if($history->new_value)
                                        <span class="badge" style="background:#e8f5e9;color:#2e7d32;font-size:0.7rem;">{{ Str::limit($history->new_value, 40) }}</span>
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size:0.7rem;margin-top:2px;">{{ $history->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <span class="material-icons-round d-block mb-1" style="font-size:2rem;opacity:0.2;">manage_history</span>
                            <span style="font-size:0.82rem;">No changes recorded yet.</span>
                        </div>
                    @endforelse
                </div>

                {{-- PANE: Activity Timeline --}}
                <div class="show-tab-pane" id="tabActivity">
                    @forelse($ticket->activities->sortByDesc('created_at') as $activity)
                        @php $cfg = $actionConfig[$activity->action] ?? ['bg' => 'linear-gradient(195deg,#42424a,#191919)', 'icon' => 'info'] @endphp
                        <div class="d-flex gap-3 {{ !$loop->last ? 'mb-4' : '' }}">
                            <div class="d-flex flex-column align-items-center" style="width:36px;">
                                <div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;background:{{ $cfg['bg'] }};
                                            display:flex;align-items:center;justify-content:center;">
                                    <span class="material-icons-round text-white" style="font-size:1rem;">{{ $cfg['icon'] }}</span>
                                </div>
                                @if(!$loop->last)<div style="width:2px;flex:1;background:#f0f2f5;margin-top:4px;"></div>@endif
                            </div>
                            <div class="flex-grow-1 pb-2">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-semibold" style="font-size:0.85rem;color:#344767;">{{ $activity->user->name }}</span>
                                    <span class="badge" style="font-size:0.65rem;padding:3px 8px;background:{{ $cfg['bg'] }};">{{ $activity->action }}</span>
                                </div>
                                <p class="mb-1" style="font-size:0.82rem;color:#344767;">{{ $activity->description }}</p>
                                <span class="text-muted" style="font-size:0.72rem;">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <span class="material-icons-round d-block mb-1" style="font-size:2rem;opacity:0.2;">history</span>
                            <span style="font-size:0.82rem;">No activity yet.</span>
                        </div>
                    @endforelse
                </div>

            </div>{{-- end pane wrapper --}}
        </div>

    </div>{{-- end left col --}}

    {{-- ===== RIGHT COLUMN ===== --}}
    <div class="col-lg-4">

        {{-- Actions Card (staff only) --}}
        @if($isStaff)
        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
            <div class="card-header text-white fw-bold"
                 style="background:linear-gradient(195deg,#42424a,#191919);border-radius:12px 12px 0 0;font-size:0.82rem;padding:12px 16px;">
                <span class="material-icons-round me-1" style="font-size:1rem;vertical-align:middle;">bolt</span>Quick Actions
            </div>
            <div class="card-body p-3 d-flex flex-column gap-2">

                @if($ticket->status !== 'Closed')
                    <button class="btn btn-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#resolveModal">
                        <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">check_circle</span>Resolve Ticket
                    </button>
                @endif

                @if($isAdmin)
                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="title" value="{{ $ticket->title }}">
                        <input type="hidden" name="description" value="{{ $ticket->description }}">
                        <input type="hidden" name="priority" value="{{ $ticket->priority }}">
                        <input type="hidden" name="assigned_to" value="{{ $ticket->assigned_to }}">
                        <input type="hidden" name="category_id" value="{{ $ticket->category_id }}">
                        <div class="d-flex gap-2">
                            <select name="status" class="form-select form-select-sm">
                                @foreach(['Open','In Progress','Closed'] as $s)
                                    <option value="{{ $s }}" {{ $ticket->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Set</button>
                        </div>
                    </form>

                    @if($technicians->isNotEmpty())
                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="title" value="{{ $ticket->title }}">
                        <input type="hidden" name="description" value="{{ $ticket->description }}">
                        <input type="hidden" name="priority" value="{{ $ticket->priority }}">
                        <input type="hidden" name="status" value="{{ $ticket->status }}">
                        <input type="hidden" name="category_id" value="{{ $ticket->category_id }}">
                        <div class="d-flex gap-2">
                            <select name="assigned_to" class="form-select form-select-sm">
                                <option value="">Unassigned</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ $ticket->assigned_to == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Assign</button>
                        </div>
                    </form>
                    @endif
                @endif

                @can('update', $ticket)
                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-outline-secondary btn-sm w-100">
                        <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">edit</span>Full Edit
                    </a>
                @endcan

            </div>
        </div>
        @endif

        {{-- Ticket Details --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
            <div class="card-header fw-bold"
                 style="background:#fafafa;border-radius:12px 12px 0 0;font-size:0.82rem;padding:12px 16px;color:#344767;">
                <span class="material-icons-round me-1" style="font-size:1rem;vertical-align:middle;">info</span>Ticket Details
            </div>
            <div class="card-body p-3">
                <div class="mb-2">
                    <div style="font-size:0.7rem;color:#7b809a;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Priority</div>
                    <div style="margin-top:2px;"><x-ticket-priority :value="$ticket->priority" /></div>
                </div>
                <div class="mb-2">
                    <div style="font-size:0.7rem;color:#7b809a;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Reporter</div>
                    <div style="font-size:0.82rem;color:#344767;">{{ $ticket->user->name }}</div>
                </div>
                <div class="mb-2">
                    <div style="font-size:0.7rem;color:#7b809a;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Assigned To</div>
                    <div style="font-size:0.82rem;color:#344767;">{{ $ticket->technician?->name ?? '—' }}</div>
                </div>
                @if($ticket->category)
                <div class="mb-2">
                    <div style="font-size:0.7rem;color:#7b809a;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Category</div>
                    <div style="font-size:0.82rem;color:#344767;">{{ $ticket->category->name }}</div>
                </div>
                @endif
                @if($ticket->due_date)
                <div class="mb-2">
                    <div style="font-size:0.7rem;color:#7b809a;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Due Date</div>
                    <div style="font-size:0.82rem;color:{{ $ticket->due_date->isPast() && $ticket->status !== 'Closed' ? '#c62828' : '#344767' }};">
                        {{ $ticket->due_date->format('d M Y') }}
                        @if($ticket->due_date->isPast() && $ticket->status !== 'Closed')
                            <span class="badge" style="background:#fdecea;color:#c62828;font-size:0.65rem;">Overdue</span>
                        @endif
                    </div>
                </div>
                @endif
                <div class="mb-2">
                    <div style="font-size:0.7rem;color:#7b809a;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Created</div>
                    <div style="font-size:0.82rem;color:#344767;">{{ $ticket->created_at->format('d M Y, H:i') }}</div>
                </div>
                @if($ticket->resolved_at)
                <div>
                    <div style="font-size:0.7rem;color:#7b809a;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Resolved</div>
                    <div style="font-size:0.82rem;color:#344767;">{{ $ticket->resolved_at->format('d M Y, H:i') }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Watchers --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
            <div class="card-header fw-bold"
                 style="background:#fafafa;border-radius:12px 12px 0 0;font-size:0.82rem;padding:12px 16px;color:#344767;">
                <span class="material-icons-round me-1" style="font-size:1rem;vertical-align:middle;">visibility</span>
                Watchers
                @if($ticket->watchers->count())
                    <span class="badge rounded-pill ms-1"
                          style="background:linear-gradient(195deg,#EC407A,#D81B60);font-size:0.65rem;">
                        {{ $ticket->watchers->count() }}
                    </span>
                @endif
            </div>
            <div class="card-body p-3">
                @if($ticket->watchers->count())
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        @foreach($ticket->watchers as $watcher)
                            <span class="badge rounded-pill"
                                  style="background:#f0f2f5;color:#344767;font-size:0.75rem;padding:5px 10px;">
                                {{ $watcher->user->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                @if($isWatching)
                    <form action="{{ route('tickets.unwatch', $ticket->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                            <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">visibility_off</span>Unwatch
                        </button>
                    </form>
                @else
                    <form action="{{ route('tickets.watch', $ticket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm w-100 text-white"
                                style="background:linear-gradient(195deg,#26A69A,#00897B);">
                            <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">visibility</span>Watch
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Delete (admin) --}}
        @can('delete', $ticket)
        <div class="card border-0 shadow-sm" style="border-radius:12px;border:1px solid #fdecea !important;">
            <div class="card-body p-3">
                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                            onclick="return confirm('Permanently delete this ticket?')">
                        <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">delete_forever</span>Delete Ticket
                    </button>
                </form>
            </div>
        </div>
        @endcan

    </div>{{-- end right col --}}
</div>

{{-- Resolve Modal --}}
<div class="modal fade" id="resolveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tickets.resolve', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-header"
                     style="background:linear-gradient(195deg,#66BB6A,#43A047);border-radius:8px 8px 0 0;">
                    <h6 class="modal-title text-white fw-bold">
                        <span class="material-icons-round me-1" style="font-size:1rem;vertical-align:middle;">check_circle</span>
                        Resolve Ticket
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p style="font-size:0.85rem;color:#344767;">This will close the ticket and mark it as resolved.</p>
                    <div>
                        <label class="form-label fw-semibold" style="font-size:0.82rem;">
                            Resolution Notes <span class="text-muted">(optional)</span>
                        </label>
                        <textarea name="resolution_notes" class="form-control form-control-sm"
                                  rows="4" placeholder="Describe how the issue was resolved…"
                                  maxlength="2000"></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">
                        <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">check</span>Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function toggleEdit(id) {
    const el = document.getElementById('comment-' + id);
    if (!el) return;
    el.querySelector('.comment-body').classList.toggle('d-none');
    el.querySelector('.comment-edit').classList.toggle('d-none');
}

// Custom tab system
document.querySelectorAll('.show-tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.show-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.show-tab-pane').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.target).classList.add('active');
    });
});
</script>
@endsection
