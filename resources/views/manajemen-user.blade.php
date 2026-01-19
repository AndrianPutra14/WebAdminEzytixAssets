@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')

<button class="btn btn-outline-light d-lg-none mb-3" id="toggleSidebar">
    <i class="bi bi-list"></i>
</button>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1">Manajemen User</h4>
        <p class="text-white mb-0">Kelola akun pengguna sistem</p>
    </div>

    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addUser">
        <i class="bi bi-person-plus"></i> Tambah User
    </button>
</div>

@if ($errors->any())
<div class="alert alert-danger">{{ $errors->first() }}</div>
@endif
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card card-dark">
    <div class="card-body p-0">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Terdaftar</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td class="text-white fw-semibold">
                        {{ $user['full_name'] }}<br>
                        <small>{{ $user['username'] }}</small>
                    </td>
                    <td>{{ $user['email'] }}</td>
                    <td>
                        <span class="badge 
                            {{ $user['role']=='admin' ? 'bg-danger' : 
                               ($user['role']=='teknisi' ? 'bg-warning text-dark' : 'bg-info') }}">
                            {{ strtoupper($user['role']) }}
                        </span>
                    </td>
                    <td>{{ $user['Phone'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($user['created_at'])->format('d-m-Y') }}</td>
                    <td class="text-end">

                        {{-- RESET PASSWORD --}}
                        <button class="btn btn-link text-info p-0 me-2"
                            data-bs-toggle="modal"
                            data-bs-target="#resetPass{{ $user['id'] }}"
                            title="Reset Password">
                            <i class="bi bi-key"></i>
                        </button>

                        {{-- EDIT --}}
                        <button class="btn btn-link text-warning p-0 me-2"
                            data-bs-toggle="modal"
                            data-bs-target="#editUser{{ $user['id'] }}"
                            title="Edit User">
                            <i class="bi bi-pencil"></i>
                        </button>

                        {{-- DELETE --}}
                        <form action="{{ route('manajemen.users.delete', $user['id']) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-link text-danger p-0"
                                onclick="return confirm('Yakin hapus user ini?')"
                                title="Hapus User">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                {{-- ===================== MODAL RESET PASSWORD ===================== --}}
                <div class="modal fade" id="resetPass{{ $user['id'] }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content card-dark">
                            <form method="POST" action="{{ route('manajemen.users.reset_password', $user['id']) }}">
                                @csrf
                                <div class="modal-header border-0">
                                    <h5 class="modal-title text-white">Reset Password</h5>
                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-2 text-white">
                                        User: <strong>{{ $user['full_name'] }}</strong> ({{ $user['username'] }})
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label text-white">Password Baru</label>
                                        <input class="form-control" type="password" name="new_password" required minlength="6">
                                    </div>

                                    <div>
                                        <label class="form-label text-white">Konfirmasi Password Baru</label>
                                        <input class="form-control" type="password" name="new_password_confirmation" required minlength="6">
                                    </div>

                                    <small class="text-white d-block mt-2">
                                        Minimal 6 karakter.
                                    </small>
                                </div>

                                <div class="modal-footer border-0">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-primary-custom">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ===================== MODAL EDIT USER ===================== --}}
                <div class="modal fade" id="editUser{{ $user['id'] }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content card-dark">
                            <form method="POST" action="{{ route('manajemen.users.update', $user['id']) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-header border-0">
                                    <h5 class="modal-title text-white">Edit User</h5>
                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input class="form-control mb-2" name="full_name" value="{{ $user['full_name'] }}">
                                    <input class="form-control mb-2" name="username" value="{{ $user['username'] }}">
                                    <input class="form-control mb-2" name="email" value="{{ $user['email'] }}">
                                    <input class="form-control mb-2" name="Phone" value="{{ $user['Phone'] }}">

                                    <select class="form-select" name="role">
                                        <option value="admin" {{ $user['role']=='admin'?'selected':'' }}>Admin</option>
                                        <option value="user" {{ $user['role']=='user'?'selected':'' }}>User</option>
                                        <option value="teknisi" {{ $user['role']=='teknisi'?'selected':'' }}>Teknisi</option>
                                    </select>
                                </div>
                                <div class="modal-footer border-0">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-primary-custom">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ===================== MODAL TAMBAH USER ===================== --}}
<div class="modal fade" id="addUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-dark">
            <form method="POST" action="{{ route('manajemen.users.store') }}">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white">Tambah User</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input class="form-control mb-2" name="full_name" placeholder="Nama Lengkap" required>
                    <input class="form-control mb-2" name="username" placeholder="Username" required>
                    <input class="form-control mb-2" name="email" placeholder="Email" required>
                    <input class="form-control mb-2" name="Phone" placeholder="Nomor HP" required>

                    <select class="form-select mb-2" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                        <option value="teknisi">Teknisi</option>
                    </select>

                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary-custom">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
