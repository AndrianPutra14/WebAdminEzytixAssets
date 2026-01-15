@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<button class="btn btn-outline-light d-lg-none mb-3" id="toggleSidebar">
    <i class="bi bi-list"></i>
</button>

<div class="row">

    {{-- ================= PROFILE CARD ================= --}}
    <div class="col-lg-4 mb-4">
        <div class="card card-dark text-center">
            <div class="card-body">

                <div class="avatar-wrapper mb-3">
                    <div class="avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                </div>

                <h5 class="fw-bold text-white mb-0">
                    {{ $user['full_name'] ?? '-' }}
                </h5>
                <small class="text-white">
                    {{ $user['email'] ?? '-' }}
                </small>

                <div class="mt-3">
                    <span class="badge bg-danger">
                        {{ ucfirst($user['role'] ?? '-') }}
                    </span>
                    <span class="badge bg-success">
                        {{ ucfirst($user['status'] ?? 'aktif') }}
                    </span>
                </div>

                <hr class="border-secondary my-4">

                <div class="text-start">
                    <p class="mb-1 text-white">Terdaftar sejak</p>
                    <p class="fw-semibold text-white">
                        {{ $user['created_at'] ?? '-' }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= FORM PROFILE ================= --}}
    <div class="col-lg-8">

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- ================= DATA PROFILE ================= --}}
        <div class="card card-dark mb-4">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="bi bi-person"></i> Informasi Profile
                </h6>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input
                                type="text"
                                class="form-control"
                                name="full_name"
                                value="{{ old('full_name', $user['full_name'] ?? '') }}"
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                name="email"
                                value="{{ old('email', $user['email'] ?? '') }}"
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ ucfirst($user['role'] ?? '') }}"
                                disabled
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ ucfirst($user['status'] ?? 'aktif') }}"
                                disabled
                            >
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <button class="btn btn-primary-custom">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ================= GANTI PASSWORD ================= --}}
        <div class="card card-dark">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="bi bi-lock"></i> Ganti Password
                </h6>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Password Lama</label>
                            <input
                                type="password"
                                class="form-control"
                                name="old_password"
                            >
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Password Baru</label>
                            <input
                                type="password"
                                class="form-control"
                                name="password"
                            >
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Konfirmasi Password</label>
                            <input
                                type="password"
                                class="form-control"
                                name="password_confirmation"
                            >
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <button class="btn btn-danger">
                            <i class="bi bi-shield-lock"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
