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

{{-- FLASH MESSAGE --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->has('error'))
    <div class="alert alert-danger">{{ $errors->first('error') }}</div>
@endif
@if($errors->has('auth'))
    <div class="alert alert-warning">{{ $errors->first('auth') }}</div>
@endif

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

                        <td>{{ \Carbon\Carbon::parse($report['created_at'])->format('Y-m-d H:i') }}</td>

                        <td class="text-end">
                            {{-- EDIT --}}
                            <a href="javascript:void(0)"
                               class="me-2 text-white"
                               data-bs-toggle="modal"
                               data-bs-target="#editReportModal"
                               data-id="{{ $report['id'] }}"
                               data-tvm_code="{{ $report['tvm_code'] }}"
                               data-location="{{ $report['location'] }}"
                               data-issue_type="{{ $report['issue_type'] }}"
                               data-priority="{{ $report['priority'] }}"
                               data-status="{{ $report['status'] }}"
                               data-barang_id="{{ $report['barang_id'] ?? '' }}"
                               data-description="{{ $report['description'] ?? '' }}">
                                <i class="bi bi-pencil"></i>
                            </a>

                            {{-- DELETE --}}
                            <a href="javascript:void(0)"
                               class="text-danger"
                               data-bs-toggle="modal"
                               data-bs-target="#deleteReportModal"
                               data-id="{{ $report['id'] }}"
                               data-code="TVM-{{ str_pad($report['id'], 3, '0', STR_PAD_LEFT) }}"
                               data-tvm_code="{{ $report['tvm_code'] }}">
                                <i class="bi bi-trash text-danger"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Belum ada ticket maintenance
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===================== MODAL EDIT ===================== --}}
<div class="modal fade" id="editReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-custom">
            <div class="modal-header">
                <h5 class="modal-title">Edit Ticket</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="editReportForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <input type="hidden" id="edit_barang_id" name="barang_id">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Machine ID</label>
                            <input type="text" class="form-control" id="edit_tvm_code" name="tvm_code" readonly>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="edit_location" name="location">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Issue</label>
                            <input type="text" class="form-control" id="edit_issue_type" name="issue_type">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Foto (wajib upload ulang)</label>
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*" required>
                            <small class="text-muted">Karena API mewajibkan image saat update.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <select class="form-select" id="edit_priority" name="priority">
                                <option value="urgent">urgent</option>
                                <option value="high">high</option>
                                <option value="medium">medium</option>
                                <option value="low">low</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="pending">pending</option>
                                <option value="in_progress">in progress</option>
                                <option value="resolved">resolved</option>
                                <option value="closed">closed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===================== MODAL DELETE ===================== --}}
<div class="modal fade" id="deleteReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-custom">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Ticket</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="deleteReportForm" method="POST" action="">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <p>Yakin ingin menghapus ticket ini?</p>
                    <div class="p-3 rounded bg-black border">
                        <div><strong id="delete_code">-</strong></div>
                        <small>Machine: <span id="delete_tvm_code">-</span></small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

  // BASE ke route Laravel (bukan API Go)
  const ADMIN_REPORTS_BASE = @json(url('/admin/reports'));

  const editModal = document.getElementById('editReportModal');
  const deleteModal = document.getElementById('deleteReportModal');

  // ===== EDIT MODAL =====
  editModal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    if (!btn) return;

    const id = btn.getAttribute('data-id');

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_barang_id').value = btn.getAttribute('data-barang_id') ?? '';
    document.getElementById('edit_tvm_code').value = btn.getAttribute('data-tvm_code') ?? '';
    document.getElementById('edit_location').value = btn.getAttribute('data-location') ?? '';
    document.getElementById('edit_issue_type').value = btn.getAttribute('data-issue_type') ?? '';
    document.getElementById('edit_description').value = btn.getAttribute('data-description') ?? '';
    document.getElementById('edit_priority').value = btn.getAttribute('data-priority') ?? 'medium';
    document.getElementById('edit_status').value = btn.getAttribute('data-status') ?? 'pending';

    // reset file input (biar user wajib pilih ulang)
    const fileInput = document.getElementById('edit_image');
    if (fileInput) fileInput.value = '';

    // action ke Laravel
    document.getElementById('editReportForm').action = `${ADMIN_REPORTS_BASE}/${id}`;
  });

  // Reset modal biar tidak kebawa data lama
  editModal.addEventListener('hidden.bs.modal', function () {
    document.getElementById('editReportForm').reset();
    document.getElementById('edit_id').value = '';
    document.getElementById('edit_barang_id').value = '';
    document.getElementById('editReportForm').action = '';
  });

  // ===== DELETE MODAL =====
  deleteModal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    if (!btn) return;

    const id = btn.getAttribute('data-id');
    document.getElementById('delete_code').textContent = btn.getAttribute('data-code') ?? '-';
    document.getElementById('delete_tvm_code').textContent = btn.getAttribute('data-tvm_code') ?? '-';

    document.getElementById('deleteReportForm').action = `${ADMIN_REPORTS_BASE}/${id}`;
  });

});
</script>
@endpush
