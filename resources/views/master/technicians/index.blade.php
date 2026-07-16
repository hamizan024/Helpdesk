@extends('layouts.app')

@section('title', 'Technicians')

@section('content')

@if(session('success'))
    <x-alert type="success" class="mb-3">{{ session('success') }}</x-alert>
@endif

<x-app-card title="Technicians" icon="engineering" :badge="$items->total()" :noPad="true">
    <x-slot:actions>
        <form method="GET" action="{{ route('master.technicians.index') }}" class="d-flex">
            <div class="input-group input-group-sm" style="width:220px;">
                <input type="text" name="search" value="{{ $search }}"
                       class="form-control" placeholder="Search technicians…">
                <button class="btn btn-outline-secondary" type="submit">
                    <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;">search</span>
                </button>
                @if($search)
                    <a href="{{ route('master.technicians.index') }}" class="btn btn-outline-secondary" title="Clear">
                        <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;">close</span>
                    </a>
                @endif
            </div>
        </form>
    </x-slot:actions>

    <div class="px-4 pt-3">
        <p class="text-muted mb-0" style="font-size:0.8rem;">
            Menentukan departemen tiap teknisi — satu teknisi bisa memegang lebih dari satu departemen
            (misalnya IT support yang menangani banyak departemen/toko). Tiket baru dengan kategori dari
            departemen yang dicentang akan otomatis ditugaskan ke teknisi yang beban tiket aktifnya paling sedikit.
        </p>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:0.84rem;">
            <thead style="background:#fafafa;">
                <tr>
                    <th class="px-4 py-3 border-0" style="width:3rem;color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">#</th>
                    <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Name</th>
                    <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Email</th>
                    <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Departments</th>
                    <th class="px-4 py-3 border-0 text-end" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Active Tickets</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $tech)
                    @php $assignedIds = $tech->departments->pluck('id'); @endphp
                    <tr>
                        <td class="px-4 py-3" style="color:#7b809a;">{{ $items->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 fw-semibold" style="color:#344767;">{{ $tech->name }}</td>
                        <td class="px-4 py-3" style="color:#7b809a;">{{ $tech->email }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('master.technicians.update', $tech) }}" class="d-flex align-items-start gap-2">
                                @csrf
                                @method('PATCH')
                                <div class="d-flex flex-wrap gap-2" style="max-width:320px;">
                                    @foreach($departments as $dept)
                                        <div class="form-check form-check-inline m-0" style="font-size:0.78rem;">
                                            <input class="form-check-input" type="checkbox" name="department_ids[]"
                                                   value="{{ $dept->id }}" id="dept{{ $tech->id }}_{{ $dept->id }}"
                                                   {{ $assignedIds->contains($dept->id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="dept{{ $tech->id }}_{{ $dept->id }}">
                                                {{ $dept->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-primary" style="font-size:0.75rem;padding:3px 10px;white-space:nowrap;">
                                    Save
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-end" style="color:#7b809a;">
                            {{ $tech->assignedTickets()->whereIn('status', ['Open', 'In Progress'])->count() }} active tickets
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-muted">
                            {{ $search ? 'No technicians match your search.' : 'No technician accounts yet.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $items->links() }}
        </div>
    @endif

</x-app-card>

@endsection
