<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $ticket->ticket_number }} — IT Helpdesk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; font-size: 13px; color: #344767; background: #fff; padding: 32px; }
        h1 { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
        h2 { font-size: 13px; font-weight: 600; color: #7b809a; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 12px; margin-top: 24px; border-bottom: 1px solid #f0f2f5; padding-bottom: 6px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; border-bottom: 2px solid #f0f2f5; padding-bottom: 16px; }
        .ticket-number { font-size: 11px; color: #7b809a; margin-top: 2px; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #fff; }
        .badge-open { background: #42424a; }
        .badge-inprogress { background: #1A73E8; }
        .badge-closed { background: #43A047; }
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
        .meta-item label { display: block; font-size: 10px; color: #7b809a; text-transform: uppercase; letter-spacing: .05em; font-weight: 600; margin-bottom: 2px; }
        .meta-item span { font-size: 12px; }
        .description { background: #fafafa; border-radius: 8px; padding: 14px; line-height: 1.7; white-space: pre-wrap; margin-bottom: 20px; }
        .resolution { background: #e8f5e9; border-left: 4px solid #43A047; border-radius: 4px; padding: 12px; margin-bottom: 20px; }
        .resolution-label { font-size: 10px; color: #2e7d32; text-transform: uppercase; font-weight: 600; margin-bottom: 4px; }
        .comment { display: flex; gap: 12px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #f0f2f5; }
        .comment-avatar { width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(195deg, #EC407A, #D81B60); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: 700; flex-shrink: 0; }
        .comment-body { flex: 1; }
        .comment-meta { font-size: 11px; color: #7b809a; margin-bottom: 4px; }
        .comment-meta strong { color: #344767; }
        .comment-text { background: #f8f9fa; border-radius: 4px; padding: 8px 12px; line-height: 1.6; }
        .timeline-item { display: flex; gap: 12px; margin-bottom: 12px; align-items: flex-start; }
        .timeline-dot { width: 24px; height: 24px; border-radius: 50%; background: #42424a; flex-shrink: 0; }
        .attachment-item { display: inline-flex; align-items: center; gap: 6px; background: #f8f9fa; padding: 4px 10px; border-radius: 4px; margin: 4px; font-size: 11px; }
        .print-footer { margin-top: 32px; padding-top: 12px; border-top: 1px solid #f0f2f5; font-size: 11px; color: #7b809a; text-align: center; }
        @media print {
            body { padding: 16px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    {{-- Print toolbar (hidden when printing) --}}
    <div class="no-print" style="margin-bottom:16px;display:flex;gap:8px;align-items:center;">
        <button onclick="window.print()"
                style="background:linear-gradient(195deg,#EC407A,#D81B60);color:#fff;border:none;padding:8px 20px;border-radius:6px;font-size:13px;cursor:pointer;font-weight:600;">
            🖨 Print
        </button>
        <button onclick="window.close()"
                style="background:#f0f2f5;border:none;padding:8px 16px;border-radius:6px;font-size:13px;cursor:pointer;">
            Close
        </button>
    </div>

    <div class="header">
        <div>
            <h1>{{ $ticket->title }}</h1>
            <div class="ticket-number">{{ $ticket->ticket_number }} · IT Helpdesk</div>
        </div>
        <div>
            @php
                $badgeClass = match($ticket->status) {
                    'Open' => 'badge-open',
                    'In Progress' => 'badge-inprogress',
                    default => 'badge-closed',
                };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ $ticket->status }}</span>
        </div>
    </div>

    <div class="meta-grid">
        <div class="meta-item">
            <label>Priority</label>
            <span>{{ $ticket->priority }}</span>
        </div>
        <div class="meta-item">
            <label>Reporter</label>
            <span>{{ $ticket->user->name }}</span>
        </div>
        <div class="meta-item">
            <label>Assigned To</label>
            <span>{{ $ticket->technician?->name ?? '—' }}</span>
        </div>
        <div class="meta-item">
            <label>Category</label>
            <span>{{ $ticket->category?->name ?? '—' }}</span>
        </div>
        <div class="meta-item">
            <label>Created</label>
            <span>{{ $ticket->created_at->format('d M Y, H:i') }}</span>
        </div>
        @if($ticket->due_date)
        <div class="meta-item">
            <label>Due Date</label>
            <span>{{ $ticket->due_date->format('d M Y') }}</span>
        </div>
        @endif
        @if($ticket->resolved_at)
        <div class="meta-item">
            <label>Resolved</label>
            <span>{{ $ticket->resolved_at->format('d M Y, H:i') }}</span>
        </div>
        @endif
    </div>

    <h2>Description</h2>
    <div class="description">{{ $ticket->description }}</div>

    @if($ticket->resolution_notes)
        <div class="resolution">
            <div class="resolution-label">✓ Resolution Notes</div>
            <div>{{ $ticket->resolution_notes }}</div>
        </div>
    @endif

    @if($ticket->attachments->count())
        <h2>Attachments ({{ $ticket->attachments->count() }})</h2>
        <div style="margin-bottom:20px;">
            @foreach($ticket->attachments as $att)
                <span class="attachment-item">📎 {{ $att->original_name }} ({{ $att->getSizeFormatted() }})</span>
            @endforeach
        </div>
    @endif

    @if($comments->where('is_internal', false)->count())
        <h2>Comments ({{ $comments->where('is_internal', false)->count() }})</h2>
        @foreach($comments->where('is_internal', false) as $comment)
            <div class="comment">
                <div class="comment-avatar">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</div>
                <div class="comment-body">
                    <div class="comment-meta">
                        <strong>{{ $comment->user->name }}</strong> · {{ $comment->created_at->format('d M Y, H:i') }}
                    </div>
                    <div class="comment-text">{{ $comment->message }}</div>
                </div>
            </div>
        @endforeach
    @endif

    @if($ticket->activities->count())
        <h2>Activity Log</h2>
        @foreach($ticket->activities->sortBy('created_at') as $activity)
            <div class="timeline-item">
                <div class="timeline-dot" style="background:{{ match($activity->action) {
                    'create' => '#43A047', 'assign' => '#1A73E8', 'status' => '#FB8C00',
                    'resolve' => '#00ACC1', default => '#42424a'
                } }};"></div>
                <div>
                    <div style="font-size:12px;"><strong>{{ $activity->user->name }}</strong> — {{ $activity->description }}</div>
                    <div style="font-size:10px;color:#7b809a;">{{ $activity->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        @endforeach
    @endif

    <div class="print-footer">
        IT Helpdesk · Printed {{ now()->format('d M Y H:i') }}
    </div>

</body>
</html>
