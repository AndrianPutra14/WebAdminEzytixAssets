@extends('layouts.app')

@section('title', 'History Maintenance')

@section('content')
<button class="btn btn-outline-light d-lg-none mb-3" id="toggleSidebar">
    <i class="bi bi-list"></i>
</button>

{{-- HEADER --}}
<div class="mb-4">
    <h4 class="fw-bold text-white mb-1">History Maintenance</h4>
    <p class="text-white mb-0">Timeline semua aktivitas maintenance ticket</p>
</div>

@if($errors->has('error'))
    <div class="alert alert-danger">{{ $errors->first('error') }}</div>
@endif
@if($errors->has('auth'))
    <div class="alert alert-warning">{{ $errors->first('auth') }}</div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- TIMELINE CARD --}}
<div class="card card-dark">
    <div class="card-body">

        <h6 class="fw-semibold mb-4">
            <i class="bi bi-clock-history me-2"></i> Timeline Activity
        </h6>

        <div class="timeline">

            @forelse(($history ?? []) as $item)
                @php
                    $reportId = $item['_report_id'] ?? $item['TVMReportID'] ?? null;

                    $fromStatus = strtolower($item['FromStatus'] ?? '-');
                    $toStatus   = strtolower($item['ToStatus'] ?? '-');

                    $role = $item['Role']
                        ?? ($item['changed_by']['role'] ?? '-');

                    $userName = $item['changed_by']['full_name']
                        ?? $item['changed_by']['username']
                        ?? 'Unknown';

                    $createdAt = $item['CreatedAt'] ?? null;
                    $timeText = $createdAt
                        ? \Carbon\Carbon::parse($createdAt)->format('Y-m-d H:i')
                        : '-';

                    // warna dot per status tujuan (toStatus)
                    $dotClass = match ($toStatus) {
                        'pending' => 'bg-primary',
                        'in_progress' => 'bg-warning',
                        'resolved' => 'bg-success',
                        'closed' => 'bg-secondary',
                        default => 'bg-light',
                    };

                    // badge class status (pakai class badge kamu di layout)
                    $badgeClass = match ($toStatus) {
                        'pending' => 'badge-open',
                        'in_progress' => 'badge-progress',
                        'resolved' => 'badge-resolved',
                        'closed' => 'badge-closed',
                        default => 'badge-secondary',
                    };

                    $ticketCode = $reportId !== null
                        ? 'TVM-' . str_pad((int)$reportId, 3, '0', STR_PAD_LEFT)
                        : '-';

                    $fromLabel = str_replace('_', ' ', $fromStatus);
                    $toLabel   = str_replace('_', ' ', $toStatus);
                @endphp

                <div class="timeline-item">
                    <div class="timeline-dot {{ $dotClass }}"></div>

                    <div class="timeline-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-semibold">{{ $ticketCode }}</span>
                                <span class="badge {{ $badgeClass }} ms-2">{{ $toLabel }}</span>

                                <div class="mt-1 text-white">
                                    <small class="text-white">
                                        Status: <span class="text-white">{{ $fromLabel }}</span>
                                        <i class="bi bi-arrow-right mx-1"></i>
                                        <span class="text-white">{{ $toLabel }}</span>
                                    </small>
                                </div>
                            </div>

                            <small class="text-white">{{ $timeText }}</small>
                        </div>

                        <div class="mt-2">
                            <small class="text-white">
                                Diubah oleh: <span class="fw-semibold">{{ $userName }}</span>
                                <span class="badge bg-light text-dark ms-2">{{ $role }}</span>
                            </small>
                        </div>
                    </div>
                </div>

            @empty
                <div class="text-center text-muted py-4">
                    Belum ada history.
                </div>
            @endforelse

        </div>
    </div>
</div>

@endsection
