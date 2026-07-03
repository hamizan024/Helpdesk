@extends('layouts.app')

@section('title', 'Ticket List')

@section('content')

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center py-3 px-4"
             style="background: linear-gradient(195deg, #42424a, #191919); border-radius: 12px 12px 0 0;">
            <h6 class="text-white mb-0 fw-bold">Ticket List</h6>
            <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-light">
                <span class="material-icons-round" style="font-size:1rem; vertical-align:middle;">add</span>
                Create Ticket
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Ticket Number</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Technician</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr>
                                <td class="ps-4">{{ $loop->iteration }}</td>
                                <td>{{ $ticket->ticket_number }}</td>
                                <td>
                                    <div class="fw-semibold" style="font-size:0.875rem;">{{ $ticket->title }}</div>
                                    <div class="text-muted" style="font-size:0.78rem;">{{ Str::limit($ticket->description, 50) }}</div>
                                </td>
                                <td class="text-center">
                                    <x-ticket-priority :value="$ticket->priority" />
                                </td>
                                <td class="text-center">
                                    <x-ticket-status :value="$ticket->status" />
                                </td>
                                <td>
                                    {{ $ticket->technician?->name ?? '-' }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
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
                                            <form action="{{ route('tickets.destroy', $ticket->id) }}"
                                                  method="POST" class="d-inline">
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
                                    <span class="material-icons-round d-block mb-2" style="font-size:2rem; opacity:0.3;">inbox</span>
                                    No tickets found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            @if ($tickets->hasPages())
                <div class="d-flex justify-content-center py-3 px-4">
                    {{ $tickets->links() }}
                </div>
            @endif

        </div>

    </div>

@endsection
