@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<button class="btn btn-outline-light d-lg-none mb-3" id="toggleSidebar">
    <i class="bi bi-list"></i>
</button>

<h2 class="fw-bold text-white mb-1">Dashboard</h2>
<p class="text-white">Overview sistem maintenance ticket vending machine</p>

{{-- ================= STAT CARD ================= --}}
<div class="row g-3 mt-3">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <span>Total Tickets</span>
                <i class="bi bi-ticket"></i>
            </div>
            <h3>{{ $stats['total'] ?? 0 }}</h3>
            <small class="text-all">Semua ticket maintenance</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <span>Pending</span>
                <i class="bi bi-info-circle text-primary"></i>
            </div>
            <h3>{{ $stats['pending'] ?? 0 }}</h3>
            <small class="text-all">Belum ditangani</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <span>In Progress</span>
                <i class="bi bi-clock text-warning"></i>
            </div>
            <h3>{{ $stats['in_progress'] ?? 0 }}</h3>
            <small class="text-all">Sedang dikerjakan</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <span>Resolved</span>
                <i class="bi bi-check-circle text-success"></i>
            </div>
            <h3>{{ $stats['resolved'] ?? 0 }}</h3>
            <small class="text-all">Selesai diperbaiki</small>
        </div>
    </div>
</div>

{{-- ================= TICKET TERBARU ================= --}}
<div class="card mt-4 card-custom">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Data Maintenance</h6>
            {{-- <a href="#" class="btn btn-sm btn-outline-secondary text-white">Lihat Semua</a> --}}
        </div>

        @forelse ($latestReports as $report)
        <div class="ticket-item">
            <div>
                <span class="ticket-id">
                    #TKT-{{ str_pad($report['id'], 3, '0', STR_PAD_LEFT) }}
                </span>

                {{-- PRIORITY --}}
                <span class="badge
                    @if($report['priority'] === 'high') bg-warning text-dark
                    @elseif($report['priority'] === 'critical') bg-danger
                    @else bg-secondary
                    @endif
                ">
                    {{ strtoupper($report['priority']) }}
                </span>

                <div class="fw-semibold mt-1">
                    {{ $report['tvm_code'] }} - {{ $report['location'] }}
                </div>

                <small class="text-white">
                    {{ $report['issue_type'] }}
                </small>
            </div>

            <div class="text-end">
                {{-- STATUS --}}
                <span class="badge
                    @if($report['status'] === 'pending') bg-primary
                    @elseif($report['status'] === 'in_progress') bg-warning text-dark
                    @elseif($report['status'] === 'resolved') bg-success
                    @else bg-secondary
                    @endif
                ">
                    {{ str_replace('_', ' ', $report['status']) }}
                </span>

                <div class="ticket-date text-white">
                    {{ \Carbon\Carbon::parse($report['created_at'])->format('Y-m-d H:i') }}
                </div>
            </div>
        </div>
        @empty
        <p class="text-center text-muted">
            Belum ada ticket terbaru
        </p>
        @endforelse

    </div>
</div>
@endsection
