@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/owner/admins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner/roles.css') }}">
@endpush

@section('content')
    <div class="box admin-form-box">
        <h2 class="admin-page-title">Edit Data Admin</h2>

        @if ($errors->any())
            <div class="admin-alerts error">
                <ul class="list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.admins.update', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group-admin">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="input-admin">
            </div>

            <div class="form-group-admin">
                <label>Alamat Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="input-admin">
            </div>

            <div class="form-group-admin">
                <label>Jabatan (Role Pegawai)</label>
                <select name="admin_role_id" class="input-admin">
                    <option value="">-- Tidak Memiliki Jabatan Khusus (Hanya Dashboard) --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $admin->admin_role_id == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group-admin">
                <label>Ganti Kata Sandi (Opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ganti password"
                    class="input-admin">
            </div>

            <div class="form-group-admin mb-lg">
                <label>Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password baru (jika diisi)"
                    class="input-admin">
            </div>

            <div class="admin-controls">
                <a href="{{ route('owner.admins.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection