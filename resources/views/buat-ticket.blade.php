@extends('layouts.app')

@section('title', 'Buat Ticket')

@section('content')
<button class="btn btn-outline-light d-lg-none mb-3" id="toggleSidebar">
    <i class="bi bi-list"></i>
</button>
<h4 class="fw-bold text-white mb-1">Buat Ticket Maintenance</h4>
<p class="text-white mb-4">Tambahkan ticket baru</p>

<div class="card card-dark">
    <div class="card-body">

        <form action="{{ route('buat.ticket') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="row g-3">

                {{-- BARANG --}}
                <div class="col-md-6">
                    <label class="form-label">Barang *</label>
                    <select name="barang_id" class="form-select" required>
                        <option value="">Pilih Barang</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang['id'] }}">
                                {{ $barang['nama_barang'] }} (Stok: {{ $barang['stok'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TVM --}}
                <div class="col-md-6">
                    <label class="form-label">Machine ID *</label>
                    <input type="text"
                           name="tvm_code"
                           class="form-control"
                           required>
                </div>

                {{-- LOKASI --}}
                <div class="col-md-6">
                    <label class="form-label">Lokasi *</label>
                    <input type="text"
                           name="location"
                           class="form-control"
                           required>
                </div>

                {{-- JENIS MASALAH --}}
                <div class="col-md-6">
                    <label class="form-label">Jenis Masalah *</label>
                    <select name="issue_type" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="Tidak Berfungsi">Tidak Berfungsi</option>
                        <option value="Produk Macet">Produk Macet</option>
                        <option value="Display Error">Display Error</option>
                        <option value="Pembayaran Error">Pembayaran Error</option>
                        <option value="Koneksi Jaringan">Koneksi Jaringan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                {{-- DESKRIPSI --}}
                <div class="col-md-12">
                    <label class="form-label">Deskripsi *</label>
                    <textarea name="description"
                              class="form-control"
                              rows="3"
                              required></textarea>
                </div>

                {{-- PRIORITY --}}
                <div class="col-md-4">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="">Pilih</option>
                        <option value="urgent">Urgent</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                {{-- IMAGE --}}
                <div class="col-md-12">
                    <label class="form-label">Gambar (Opsional)</label>
                    <input type="file"
                           name="image"
                           class="form-control">
                </div>

            </div>

            <div class="mt-4 text-end">
                <button class="btn btn-danger">
                    Simpan Ticket
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
