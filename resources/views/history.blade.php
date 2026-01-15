@extends('layouts.app')

@section('title', 'History Maintenance')

@section('content')
<button class="btn btn-outline-light d-lg-none mb-3" id="toggleSidebar">
    <i class="bi bi-list"></i>
</button>
{{-- HEADER --}}
<div class="mb-4">
    <h4 class="fw-bold text-white mb-1">History Maintenance</h4>
    <p class="text-muted mb-0">Timeline semua aktivitas maintenance ticket</p>
</div>

{{-- TIMELINE CARD --}}
<div class="card card-dark">
    <div class="card-body">

        <h6 class="fw-semibold mb-4">
            <i class="bi bi-clock-history me-2"></i> Timeline Activity
        </h6>

        <div class="timeline">

            {{-- ITEM --}}
            <div class="timeline-item">
                <div class="timeline-dot bg-primary"></div>

                <div class="timeline-content">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="fw-semibold">#TKT-003</span>
                            <span class="badge badge-high ms-2">high</span>
                            <span class="badge badge-open ms-1">open</span>
                        </div>
                        <small class="text-white">2024-12-19 11:00</small>
                    </div>

                    <div class="mt-2">
                        <div class="fw-semibold">VM-A330-09</div>
                        <small class="text-white">Cabin - Economy Class</small>
                        <div class="mt-1">Issue: Tidak Berfungsi</div>
                    </div>
                </div>
            </div>

            {{-- ITEM --}}
            <div class="timeline-item">
                <div class="timeline-dot bg-danger"></div>

                <div class="timeline-content">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="fw-semibold">#TKT-001</span>
                            <span class="badge badge-critical ms-2">critical</span>
                            <span class="badge badge-open ms-1">open</span>
                        </div>
                        <small class="text-white">2024-12-19 08:30</small>
                    </div>

                    <div class="mt-2">
                        <div class="fw-semibold">VM-A380-01</div>
                        <small class="text-white">Cabin - First Class</small>
                        <div class="mt-1">Issue: Tidak Berfungsi</div>
                        <small class="text-white">Assigned to: teknisi</small>
                    </div>
                </div>
            </div>

            {{-- ITEM --}}
            <div class="timeline-item">
                <div class="timeline-dot bg-warning"></div>

                <div class="timeline-content">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="fw-semibold">#TKT-002</span>
                            <span class="badge badge-high ms-2">high</span>
                            <span class="badge badge-progress ms-1">in-progress</span>
                        </div>
                        <small class="text-white">2024-12-18 14:20</small>
                    </div>

                    <div class="mt-2">
                        <div class="fw-semibold">VM-B777-05</div>
                        <small class="text-white">Galley - Rear</small>
                        <div class="mt-1">Issue: Produk Macet</div>
                        <small class="text-white">Assigned to: teknisi</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
