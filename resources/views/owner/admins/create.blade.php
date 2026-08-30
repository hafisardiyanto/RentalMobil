@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/owner/admins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner/roles.css') }}">
@endpush

@section('content')
    <div class="box admin-form-box">
        <h2 class="admin-page-title">Pendaftaran Admin Baru</h2>

        @if ($errors->any())
            <div class="admin-alerts error">
                <ul class="list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.admins.store') }}" method="POST">
            @csrf

            <div class="form-group-admin">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="input-admin">
            </div>

            <div class="form-group-admin">
                <label>Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="input-admin">
            </div>

            <div class="form-group-admin">
                <label>Jabatan (Role Pegawai)</label>
                <select name="admin_role_id" class="input-admin">
                    <option value="">-- Tidak Memiliki Jabatan Khusus (Hanya Dashboard) --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group-admin">
                <label>Kata Sandi (Password)</label>
                <input type="password" name="password" required class="input-admin">
            </div>

            <div class="form-group-admin mb-lg">
                <label>Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" required class="input-admin">
            </div>

            <div class="admin-controls">
                <a href="{{ route('owner.admins.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary btn-save">Daftarkan Admin</button>
            </div>
        </form>
    </div>
@endsection