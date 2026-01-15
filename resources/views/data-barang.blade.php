@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<button class="btn btn-outline-light d-lg-none mb-3" id="toggleSidebar">
    <i class="bi bi-list"></i>
</button>

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h4 class="fw-bold text-white mb-1">Data Barang</h4>
        <p class="text-white mb-0">Kelola semua inventaris barang vending machine</p>
    </div>
    <div class="text-end">
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#tambahBarangModal">
            <i class="bi bi-plus-circle"></i> Tambah Barang Baru
        </button>
        <span class="badge bg-light text-dark ms-2">{{ count($barang) }} Items</span>
    </div>
</div>

{{-- FILTER & SEARCH --}}
<div class="card mb-4 card-custom border-custom">
    <div class="card-body">
        <form action="{{ route('data.barang') }}" method="GET">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama barang..."
                           value="{{ $search ?? '' }}">
                </div>
                <div class="col-12 col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary-custom w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- TABLE --}}
<div class="card card-dark border-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barang as $item)
                    <tr>
                        <td>#BRG-{{ str_pad($item['id'], 3, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $item['nama_barang'] }}</td>
                        <td>{{ $item['stok'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item['updated_at'])->format('Y-m-d H:i') }}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                data-bs-target="#tambahStokModal{{ $item['id'] }}">
                                <i class="bi bi-plus-circle"></i> Tambah Stok
                            </button>
                        </td>
                    </tr>

                    {{-- Modal Tambah Stok --}}
                    <div class="modal fade" id="tambahStokModal{{ $item['id'] }}" tabindex="-1"
                        aria-labelledby="tambahStokModalLabel{{ $item['id'] }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('barang.tambah.stok', $item['id']) }}">
                                @csrf
                                <div class="modal-content card-dark">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="tambahStokModalLabel{{ $item['id'] }}">
                                            Tambah Stok - {{ $item['nama_barang'] }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah Stok</label>
                                            <input type="number" name="stok_tambah" class="form-control"
                                                min="1" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary-custom">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data barang</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah Barang Baru --}}
<div class="modal fade" id="tambahBarangModal" tabindex="-1" aria-labelledby="tambahBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('barang.tambah.baru') }}">
            @csrf
            <div class="modal-content card-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahBarangModalLabel">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stok" class="form-control" min="0" value="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
