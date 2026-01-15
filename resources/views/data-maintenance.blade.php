@extends('layouts.app')

@section('title', 'Data Maintenance')

@section('content')
<button class="btn btn-outline-light d-lg-none mb-3" id="toggleSidebar">
    <i class="bi bi-list"></i>
</button>

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h4 class="fw-bold text-white mb-1">Data Maintenance</h4>
        <p class="text-white mb-0">Kelola semua ticket maintenance vending machine</p>
    </div>
    <div class="text-end">
        <span class="badge bg-light text-dark">{{ count($reports) }} Tickets</span>
    </div>
</div>

{{-- FILTER & SEARCH --}}
<form method="GET" action="{{ route('data.maintenance') }}">
    <div class="card mb-4 card-custom border-custom">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">
                <i class="bi bi-funnel"></i> Filter & Search
            </h6>

            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari machine ID, lokasi, issue..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        @php
                            $statusOptions = ['Semua Status', 'pending', 'in_progress', 'resolved', 'closed'];
                        @endphp
                        @foreach($statusOptions as $opt)
                            <option value="{{ $opt }}" 
                                {{ ($filters['status'] ?? '') === $opt ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $opt)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Priority</label>
                    <select class="form-select" name="priority">
                        @php
                            $priorityOptions = ['Semua Priority', 'urgent', 'high', 'medium', 'low'];
                        @endphp
                        @foreach($priorityOptions as $opt)
                            <option value="{{ $opt }}" 
                                {{ ($filters['priority'] ?? '') === $opt ? 'selected' : '' }}>
                                {{ ucfirst($opt) }} 
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3 text-end">
                <button class="btn btn-primary-custom">Filter</button>
            </div>
        </div>
    </div>
</form>

{{-- TABLE --}}
<div class="card card-dark border-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Machine ID</th>
                        <th>Lokasi</th>
                        <th>Issue</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                    <tr>
                        <td>TVM-{{ str_pad($report['id'], 3, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $report['tvm_code'] }}</td>
                        <td>{{ $report['location'] }}</td>
                        <td>{{ $report['issue_type'] }}</td>

                        <td>
                            <span class="badge
                                @if($report['priority'] === 'urgent') badge-urgent
                                @elseif($report['priority'] === 'high') badge-high
                                @elseif($report['priority'] === 'medium') badge-medium
                                @elseif($report['priority'] === 'low') badge-low
                                @else badge-secondary
                                @endif">
                                {{ $report['priority'] }}
                            </span>
                        </td>

                        <td>
                            <span class="badge
                                @if($report['status'] === 'pending') badge-open
                                @elseif($report['status'] === 'in_progress') badge-progress
                                @elseif($report['status'] === 'resolved') badge-resolved
                                @elseif($report['status'] === 'closed') badge-closed
                                @else badge-secondary
                                @endif">
                                {{ str_replace('_', ' ', $report['status']) }}
                            </span>
                        </td>

                        <td>{{ $report['assigned_to'] ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($report['created_at'])->format('Y-m-d H:i') }}</td>
                        <td class="text-end">
                            {{-- <a href="#" class="me-2"><i class="bi bi-eye"></i></a> --}}
                            <a href="#" class="me-2"><i class="bi bi-pencil"></i></a>
                            <a href="#"><i class="bi bi-trash text-danger"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada ticket maintenance</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
