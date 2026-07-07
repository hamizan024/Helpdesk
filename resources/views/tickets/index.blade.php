@extends('layouts.app')

@section('title', 'Ticket List')

@section('content')

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    <x-app-card title="Ticket List" :noPad="true">

        <x-slot:actions>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                {{-- Search --}}
                <form method="GET" action="{{ route('tickets.index') }}" class="d-flex" id="filterForm">
                    @foreach($filters as $k => $v)
                        @if($k !== 'search' && $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endif
                    @endforeach
                    <div class="input-group input-group-sm" style="width:220px;">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                               class="form-control" placeholder="Search ticket…">
                        <button class="btn btn-outline-secondary" type="submit">
                            <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;">search</span>
                        </button>
                        @if($filters['search'] ?? null)
                            <a href="{{ route('tickets.index', array_filter(array_merge($filters, ['search' => null]))) }}"
                               class="btn btn-outline-secondary" title="Clear search">
                                <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;">close</span>
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Filter toggle --}}
                <button class="btn btn-sm btn-outline-secondary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#filterPanel">
                    <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">tune</span>
                    Filter
                    @php $activeFilterCount = collect($filters)->forget('search')->filter()->count() @endphp
                    @if($activeFilterCount > 0)
                        <span class="badge rounded-pill text-white ms-1"
                              style="background:linear-gradient(195deg,#EC407A,#D81B60);font-size:0.65rem;">{{ $activeFilterCount }}</span>
                    @endif
                </button>

                {{-- Export --}}
                <a href="{{ route('tickets.export', $filters) }}"
                   class="btn btn-sm btn-outline-success" title="Export CSV">
                    <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">download</span>
                    Export
                </a>

                {{-- Create --}}
                <a href="{{ route('tickets.create') }}"
                   class="btn btn-sm text-white fw-bold"
                   style="background:linear-gradient(195deg,#EC407A,#D81B60);font-size:0.78rem;padding:6px 14px;">
                    <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">add</span>
                    Create Ticket
                </a>
            </div>
        </x-slot:actions>

        {{-- Filter Panel --}}
        <div class="collapse {{ $activeFilterCount > 0 ? 'show' : '' }}" id="filterPanel">
            <div class="px-4 py-3 border-bottom" style="background:#fafafa;">
                <form method="GET" action="{{ route('tickets.index') }}" class="row g-2 align-items-end">
                    @if($filters['search'] ?? null)
                        <input type="hidden" name="search" value="{{ $filters['search'] }}">
                    @endif

                    <div class="col-md-2">
                        <label class="form-label mb-1" style="font-size:0.78rem;color:#7b809a;font-weight:600;text-transform:uppercase;">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All</option>
                            @foreach(['Open','In Progress','Closed'] as $s)
                                <option value="{{ $s }}" {{ ($filters['status'] ?? '') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-1" style="font-size:0.78rem;color:#7b809a;font-weight:600;text-transform:uppercase;">Priority</label>
                        <select name="priority" class="form-select form-select-sm">
                            <option value="">All</option>
                            @foreach(['Low','Medium','High'] as $p)
                                <option value="{{ $p }}" {{ ($filters['priority'] ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(auth()->user()->isAdmin())
                    <div class="col-md-3">
                        <label class="form-label mb-1" style="font-size:0.78rem;color:#7b809a;font-weight:600;text-transform:uppercase;">Technician</label>
                        <select name="assigned_to" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="0" {{ ($filters['assigned_to'] ?? '') === '0' ? 'selected' : '' }}>Unassigned</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ ($filters['assigned_to'] ?? '') == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-2">
                        <label class="form-label mb-1" style="font-size:0.78rem;color:#7b809a;font-weight:600;text-transform:uppercase;">Category</label>
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">All</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label mb-1" style="font-size:0.78rem;color:#7b809a;font-weight:600;text-transform:uppercase;">From</label>
                        <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-1">
                        <label class="form-label mb-1" style="font-size:0.78rem;color:#7b809a;font-weight:600;text-transform:uppercase;">To</label>
                        <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-auto d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                        <a href="{{ route('tickets.index', ['search' => $filters['search'] ?? null]) }}"
                           class="btn btn-sm btn-outline-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Active filter chips --}}
        @if($activeFilterCount > 0)
        <div class="px-4 py-2 d-flex flex-wrap gap-2 border-bottom" style="background:#fff;">
            @if($filters['status'] ?? null)
                <span class="badge rounded-pill" style="background:#e3f2fd;color:#1565c0;font-size:0.72rem;padding:4px 10px;">
                    Status: {{ $filters['status'] }}
                    <a href="{{ route('tickets.index', array_filter(array_merge($filters, ['status' => null]))) }}"
                       class="text-decoration-none ms-1" style="color:#1565c0;">×</a>
                </span>
            @endif
            @if($filters['priority'] ?? null)
                <span class="badge rounded-pill" style="background:#fff3e0;color:#e65100;font-size:0.72rem;padding:4px 10px;">
                    Priority: {{ $filters['priority'] }}
                    <a href="{{ route('tickets.index', array_filter(array_merge($filters, ['priority' => null]))) }}"
                       class="text-decoration-none ms-1" style="color:#e65100;">×</a>
                </span>
            @endif
            @if($filters['assigned_to'] ?? null)
                <span class="badge rounded-pill" style="background:#e8f5e9;color:#2e7d32;font-size:0.72rem;padding:4px 10px;">
                    Technician filtered
                    <a href="{{ route('tickets.index', array_filter(array_merge($filters, ['assigned_to' => null]))) }}"
                       class="text-decoration-none ms-1" style="color:#2e7d32;">×</a>
                </span>
            @endif
            @if($filters['from'] ?? null)
                <span class="badge rounded-pill" style="background:#f3e5f5;color:#6a1b9a;font-size:0.72rem;padding:4px 10px;">
                    From: {{ $filters['from'] }}
                    <a href="{{ route('tickets.index', array_filter(array_merge($filters, ['from' => null]))) }}"
                       class="text-decoration-none ms-1" style="color:#6a1b9a;">×</a>
                </span>
            @endif
            @if($filters['to'] ?? null)
                <span class="badge rounded-pill" style="background:#f3e5f5;color:#6a1b9a;font-size:0.72rem;padding:4px 10px;">
                    To: {{ $filters['to'] }}
                    <a href="{{ route('tickets.index', array_filter(array_merge($filters, ['to' => null]))) }}"
                       class="text-decoration-none ms-1" style="color:#6a1b9a;">×</a>
                </span>
            @endif
        </div>
        @endif

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.84rem;">
                <thead style="background:#fafafa;">
                    <tr>
                        <th class="px-4 py-3 border-0" style="width:3rem;color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">#</th>
                        <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Ticket</th>
                        <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Priority</th>
                        <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                        <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Technician</th>
                        <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Created</th>
                        <th class="px-4 py-3 border-0 text-end" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td class="px-4 py-3" style="color:#7b809a;">{{ $tickets->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('tickets.show', $ticket->id) }}"
                                   class="fw-semibold text-decoration-none"
                                   style="font-size:0.875rem;color:#344767;">
                                    {{ $ticket->title }}
                                </a>
                                <div class="text-muted" style="font-size:0.72rem;">
                                    {{ $ticket->ticket_number }}
                                    @if($ticket->category)
                                        · {{ $ticket->category->name }}
                                    @endif
                                    @if($ticket->due_date)
                                        · Due {{ $ticket->due_date->format('d M') }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <x-ticket-priority :value="$ticket->priority" />
                            </td>
                            <td class="px-4 py-3">
                                <x-ticket-status :value="$ticket->status" />
                            </td>
                            <td class="px-4 py-3" style="color:#344767;">
                                {{ $ticket->technician?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3" style="color:#7b809a;font-size:0.78rem;">
                                {{ $ticket->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex align-items-center justify-content-end gap-1">
                                    <a href="{{ route('tickets.show', $ticket->id) }}"
                                       class="btn btn-link btn-sm text-info px-2 mb-0"
                                       data-bs-toggle="tooltip" title="View">
                                        <span class="material-icons-round" style="font-size:1.1rem;">visibility</span>
                                    </a>
                                    @can('update', $ticket)
                                        <a href="{{ route('tickets.edit', $ticket->id) }}"
                                           class="btn btn-link btn-sm text-warning px-2 mb-0"
                                           data-bs-toggle="tooltip" title="Edit">
                                            <span class="material-icons-round" style="font-size:1.1rem;">edit</span>
                                        </a>
                                    @endcan
                                    @can('delete', $ticket)
                                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-link btn-sm text-danger px-2 mb-0"
                                                    data-bs-toggle="tooltip" title="Delete"
                                                    onclick="return confirm('Delete this ticket?')">
                                                <span class="material-icons-round" style="font-size:1.1rem;">delete</span>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <span class="material-icons-round d-block mb-2" style="font-size:2rem;opacity:0.3;">inbox</span>
                                No tickets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="px-4 py-3 border-top">{{ $tickets->links() }}</div>
        @endif

    </x-app-card>

@endsection