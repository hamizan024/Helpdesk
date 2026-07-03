@extends('layouts.app')

@section('title', 'Dashboard')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endsection

@section('content')

{{-- ===== STAT CARDS ===== --}}
<div class="row g-4 mb-4" style="padding-top: 8px;">

    {{-- Total Tickets --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="card-top">
                <div class="icon-box bg-gradient-primary shadow-primary">
                    <span class="material-icons-round">confirmation_number</span>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total Tickets</span>
                    <span class="stat-value">{{ $totalTickets }}</span>
                </div>
            </div>
            <div class="stat-footer">
                <span class="fw-bold" style="color:#344767;">Semua</span> tiket yang masuk
            </div>
        </div>
    </div>

    {{-- Open --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="card-top">
                <div class="icon-box bg-gradient-danger shadow-danger">
                    <span class="material-icons-round">fiber_new</span>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Open</span>
                    <span class="stat-value">{{ $openTickets }}</span>
                </div>
            </div>
            <div class="stat-footer">
                <span class="fw-bold text-danger">Menunggu</span> ditangani
            </div>
        </div>
    </div>

    {{-- In Progress --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="card-top">
                <div class="icon-box bg-gradient-warning shadow-warning">
                    <span class="material-icons-round">pending_actions</span>
                </div>
                <div class="stat-info">
                    <span class="stat-label">In Progress</span>
                    <span class="stat-value">{{ $inProgressTickets }}</span>
                </div>
            </div>
            <div class="stat-footer">
                <span class="fw-bold text-warning">Sedang</span> dikerjakan
            </div>
        </div>
    </div>

    {{-- Closed --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="card-top">
                <div class="icon-box bg-gradient-success shadow-success">
                    <span class="material-icons-round">task_alt</span>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Closed</span>
                    <span class="stat-value">{{ $closedTickets }}</span>
                </div>
            </div>
            <div class="stat-footer">
                <span class="fw-bold text-success">Selesai</span> ditangani
            </div>
        </div>
    </div>

</div>

{{-- ===== CHARTS ROW ===== --}}
<div class="row g-4 mb-4">

    {{-- Ticket Overview Bar Chart --}}
    <div class="col-lg-8">
        <div class="card h-100" style="overflow: hidden;">
            <div class="p-4" style="background: linear-gradient(195deg, #42424a, #191919); border-radius: 14px 14px 0 0;">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <h6 class="text-white fw-bold mb-0" style="font-size:0.95rem;">Ticket Overview</h6>
                        <p class="mb-0" style="color:rgba(255,255,255,0.65); font-size:0.75rem;">
                            Distribusi status tiket saat ini
                        </p>
                    </div>
                    <span class="material-icons-round" style="color:rgba(255,255,255,0.6); font-size:1.25rem;">bar_chart</span>
                </div>
                <div style="height: 220px; margin-top: 16px;">
                    <canvas id="ticketStatusChart"></canvas>
                </div>
            </div>
            <div class="p-4">
                <div class="row text-center g-0">
                    <div class="col-4" style="border-right: 1px solid #f0f2f5;">
                        <p class="mb-0 fw-bold text-danger" style="font-size:1.1rem;">{{ $openTickets }}</p>
                        <p class="mb-0 text-muted" style="font-size:0.72rem;">Open</p>
                    </div>
                    <div class="col-4" style="border-right: 1px solid #f0f2f5;">
                        <p class="mb-0 fw-bold text-warning" style="font-size:1.1rem;">{{ $inProgressTickets }}</p>
                        <p class="mb-0 text-muted" style="font-size:0.72rem;">In Progress</p>
                    </div>
                    <div class="col-4">
                        <p class="mb-0 fw-bold text-success" style="font-size:1.1rem;">{{ $closedTickets }}</p>
                        <p class="mb-0 text-muted" style="font-size:0.72rem;">Closed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Priority Breakdown Donut --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <h6 class="fw-bold mb-1" style="color:#344767; font-size:0.95rem;">Priority Breakdown</h6>
                <p class="text-muted mb-3" style="font-size:0.75rem;">Distribusi berdasarkan prioritas tiket</p>

                <div class="d-flex align-items-center justify-content-center flex-grow-1" style="min-height: 180px;">
                    <canvas id="priorityChart" style="max-height: 180px;"></canvas>
                </div>

                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:10px; height:10px; border-radius:50%;
                                         background: linear-gradient(195deg, #EF5350, #E53935);
                                         display:inline-block; flex-shrink:0;"></span>
                            <span style="font-size:0.8rem; color:#344767;">High Priority</span>
                        </div>
                        <span class="badge bg-gradient-danger">{{ $highPriority }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:10px; height:10px; border-radius:50%;
                                         background: linear-gradient(195deg, #FFA726, #FB8C00);
                                         display:inline-block; flex-shrink:0;"></span>
                            <span style="font-size:0.8rem; color:#344767;">Medium Priority</span>
                        </div>
                        <span class="badge bg-gradient-warning">{{ $mediumPriority }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:10px; height:10px; border-radius:50%;
                                         background: linear-gradient(195deg, #747b8a, #495361);
                                         display:inline-block; flex-shrink:0;"></span>
                            <span style="font-size:0.8rem; color:#344767;">Low Priority</span>
                        </div>
                        <span class="badge bg-gradient-secondary">{{ $lowPriority }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ===== RECENT TICKETS TABLE ===== --}}
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-3 px-4"
                 style="background: linear-gradient(195deg, #42424a, #191919); border-radius: 12px 12px 0 0;">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-icons-round text-white" style="font-size:1.1rem;">receipt_long</span>
                    <h6 class="text-white mb-0 fw-bold">Recent Tickets</h6>
                </div>
                <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-light">
                    <span class="material-icons-round" style="font-size:0.875rem; vertical-align:middle;">open_in_new</span>
                    Lihat Semua
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Ticket No.</th>
                                <th>Judul</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTickets as $ticket)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-semibold" style="font-size:0.8rem;">
                                            {{ $ticket->ticket_number }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold" style="font-size:0.82rem;">
                                            {{ Str::limit($ticket->title, 38) }}
                                        </div>
                                        <div class="text-muted" style="font-size:0.72rem;">
                                            {{ Str::limit($ticket->description, 48) }}
                                        </div>
                                    </td>
                                    <td>
                                        <x-ticket-priority :value="$ticket->priority" />
                                    </td>
                                    <td>
                                        <x-ticket-status :value="$ticket->status" />
                                    </td>
                                    <td>
                                        <span style="font-size:0.78rem; color:#7b809a;">
                                            {{ $ticket->created_at->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="{{ route('tickets.show', $ticket->id) }}"
                                               class="btn btn-link btn-sm text-info px-2 mb-0"
                                               title="View">
                                                <span class="material-icons-round" style="font-size:1.1rem;">visibility</span>
                                            </a>
                                            @can('update', $ticket)
                                                <a href="{{ route('tickets.edit', $ticket->id) }}"
                                                   class="btn btn-link btn-sm text-warning px-2 mb-0"
                                                   title="Edit">
                                                    <span class="material-icons-round" style="font-size:1.1rem;">edit</span>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <span class="material-icons-round d-block mb-2"
                                              style="font-size:2.5rem; opacity:0.2;">inbox</span>
                                        Belum ada tiket. <a href="{{ route('tickets.create') }}">Buat tiket pertama</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
(function () {
    // ---- Bar Chart: Status Distribution ----
    const statusCtx = document.getElementById('ticketStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: ['Open', 'In Progress', 'Closed'],
            datasets: [{
                label: 'Tickets',
                data: [{{ $openTickets }}, {{ $inProgressTickets }}, {{ $closedTickets }}],
                backgroundColor: [
                    'rgba(239,83,80,0.85)',
                    'rgba(255,167,38,0.85)',
                    'rgba(102,187,106,0.85)',
                ],
                borderColor: ['#EF5350','#FFA726','#66BB6A'],
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    titleColor: '#344767',
                    bodyColor: '#7b809a',
                    borderColor: 'rgba(0,0,0,0.08)',
                    borderWidth: 1,
                    cornerRadius: 10,
                    padding: 12,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: 'rgba(255,255,255,0.72)',
                        font: { size: 12, weight: '600' }
                    },
                    border: { display: false }
                },
                y: {
                    grid: { color: 'rgba(255,255,255,0.1)' },
                    ticks: {
                        color: 'rgba(255,255,255,0.6)',
                        font: { size: 10 },
                        stepSize: 1,
                        callback: v => Number.isInteger(v) ? v : ''
                    },
                    border: { display: false }
                }
            }
        }
    });

    // ---- Donut Chart: Priority ----
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    const priorityData = [{{ $highPriority }}, {{ $mediumPriority }}, {{ $lowPriority }}];
    const hasData = priorityData.some(v => v > 0);

    new Chart(priorityCtx, {
        type: 'doughnut',
        data: {
            labels: ['High', 'Medium', 'Low'],
            datasets: [{
                data: hasData ? priorityData : [1, 1, 1],
                backgroundColor: hasData
                    ? ['rgba(239,83,80,0.9)','rgba(255,167,38,0.9)','rgba(116,123,138,0.85)']
                    : ['rgba(0,0,0,0.06)','rgba(0,0,0,0.04)','rgba(0,0,0,0.02)'],
                borderWidth: 0,
                borderRadius: 4,
                hoverOffset: hasData ? 6 : 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '74%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: hasData,
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    titleColor: '#344767',
                    bodyColor: '#7b809a',
                    borderColor: 'rgba(0,0,0,0.08)',
                    borderWidth: 1,
                    cornerRadius: 10,
                    padding: 12,
                }
            }
        }
    });
})();
</script>
@endsection
