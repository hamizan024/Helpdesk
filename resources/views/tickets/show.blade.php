@extends('layouts.app')

@section('title', 'Ticket Detail')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        {{-- Ticket Info Card --}}
        <x-app-card title="Ticket Detail" class="mb-4">
            <x-slot:actions>
                <span class="badge" style="font-size:0.75rem; padding:6px 12px;
                    background: {{ $ticket->status === 'Open' ? 'linear-gradient(195deg,#42424a,#191919)' : ($ticket->status === 'In Progress' ? 'linear-gradient(195deg,#49a3f1,#1A73E8)' : 'linear-gradient(195deg,#66BB6A,#43A047)') }}">
                    {{ $ticket->status }}
                </span>
            </x-slot:actions>
            <div class="row g-3">
                <div class="col-sm-6">
                    <x-detail-field label="Ticket Number">
                        <p class="mb-0 fw-semibold">{{ $ticket->ticket_number }}</p>
                    </x-detail-field>
                </div>
                <div class="col-sm-6">
                    <x-detail-field label="Priority">
                        <x-ticket-priority :value="$ticket->priority" />
                    </x-detail-field>
                </div>
                <div class="col-12">
                    <x-detail-field label="Title">
                        <p class="mb-0 fw-semibold">{{ $ticket->title }}</p>
                    </x-detail-field>
                </div>
                <div class="col-12">
                    <x-detail-field label="Description">
                        <p class="mb-0" style="line-height:1.6;">{{ $ticket->description }}</p>
                    </x-detail-field>
                </div>
                <div class="col-sm-6">
                    <x-detail-field label="Assigned Technician">
                        <p class="mb-0">{{ $ticket->technician?->name ?? '-' }}</p>
                    </x-detail-field>
                </div>
                <div class="col-sm-6">
                    <x-detail-field label="Created At">
                        <p class="mb-0">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                    </x-detail-field>
                </div>
            </div>
        </x-app-card>

        {{-- Comments --}}
        <x-app-card
            title="Comments"
            icon="forum"
            :badge="$ticket->comments->count() ?: null"
            class="mb-4">

            {{-- Comment List --}}
            @forelse($ticket->comments as $comment)
                <div class="d-flex gap-3 mb-4">
                    <div style="width:36px; height:36px; border-radius:50%; flex-shrink:0;
                                background: linear-gradient(195deg, #EC407A, #D81B60);
                                display:flex; align-items:center; justify-content:center;
                                color:white; font-size:0.875rem; font-weight:600;">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-semibold" style="font-size:0.85rem; color:#344767;">
                                {{ $comment->user->name }}
                            </span>
                            <span class="text-muted" style="font-size:0.72rem;">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div style="background:#f8f9fa; border-radius:0 10px 10px 10px; padding:10px 14px; font-size:0.85rem; color:#344767; line-height:1.6;">
                            {{ $comment->message }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-3">
                    <span class="material-icons-round d-block mb-1" style="font-size:2rem; opacity:0.2;">chat_bubble_outline</span>
                    <span style="font-size:0.82rem;">Belum ada komentar.</span>
                </div>
            @endforelse

            @if($ticket->comments->count())
                <hr style="border-color:#f0f2f5; margin: 8px 0 20px;">
            @endif

            {{-- Add Comment Form --}}
            <form action="{{ route('comments.store', $ticket->id) }}" method="POST">
                @csrf
                <x-form-textarea
                    label="Add Comment"
                    name="message"
                    :rows="3"
                    :value="old('message')"
                    placeholder="Tulis komentar..." />
                <button type="submit" class="btn btn-primary btn-sm px-4">
                    <span class="material-icons-round me-1" style="font-size:0.9rem; vertical-align:middle;">send</span>
                    Send
                </button>
            </form>

        </x-app-card>

        {{-- Activity Timeline --}}
        <x-app-card title="Activity Timeline" icon="timeline" class="mb-4">

            @forelse($ticket->activities->sortByDesc('created_at') as $activity)
                <div class="d-flex gap-3 {{ !$loop->last ? 'mb-4' : '' }}">
                    <div class="d-flex flex-column align-items-center" style="width:36px;">
                        <div style="width:36px; height:36px; border-radius:50%; flex-shrink:0;
                                    background: {{ $activity->action === 'create' ? 'linear-gradient(195deg,#66BB6A,#43A047)' : ($activity->action === 'assign' ? 'linear-gradient(195deg,#49a3f1,#1A73E8)' : ($activity->action === 'status' ? 'linear-gradient(195deg,#FFA726,#FB8C00)' : 'linear-gradient(195deg,#EC407A,#D81B60)')) }};
                                    display:flex; align-items:center; justify-content:center;">
                            <span class="material-icons-round text-white" style="font-size:1rem;">
                                {{ $activity->action === 'create' ? 'add_circle' : ($activity->action === 'assign' ? 'person_add' : ($activity->action === 'status' ? 'swap_horiz' : 'chat')) }}
                            </span>
                        </div>
                        @if(!$loop->last)
                            <div style="width:2px; flex:1; background:#f0f2f5; margin-top:4px;"></div>
                        @endif
                    </div>
                    <div class="flex-grow-1 pb-2">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-semibold" style="font-size:0.85rem; color:#344767;">
                                {{ $activity->user->name }}
                            </span>
                            <span class="badge" style="font-size:0.65rem; padding:3px 8px;
                                background: {{ $activity->action === 'create' ? 'linear-gradient(195deg,#66BB6A,#43A047)' : ($activity->action === 'assign' ? 'linear-gradient(195deg,#49a3f1,#1A73E8)' : ($activity->action === 'status' ? 'linear-gradient(195deg,#FFA726,#FB8C00)' : 'linear-gradient(195deg,#EC407A,#D81B60)')) }}">
                                {{ $activity->action }}
                            </span>
                        </div>
                        <p class="mb-1" style="font-size:0.82rem; color:#344767;">
                            {{ $activity->description }}
                        </p>
                        <span class="text-muted" style="font-size:0.72rem;">
                            {{ $activity->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-3">
                    <span class="material-icons-round d-block mb-1" style="font-size:2rem; opacity:0.2;">history</span>
                    <span style="font-size:0.82rem;">Belum ada aktivitas.</span>
                </div>
            @endforelse

        </x-app-card>

        <a href="{{ route('tickets.index') }}" class="btn btn-secondary btn-sm px-4">
            <span class="material-icons-round me-1" style="font-size:0.9rem; vertical-align:middle;">arrow_back</span>
            Back
        </a>

    </div>
</div>

@endsection
