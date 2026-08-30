@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/owner/roles.css') }}">
@endpush

@section('content')
    <div class="box role-form-box">
        <h2 class="role-page-title">Edit Hak Akses Jabatan</h2>

        @if ($errors->any())
            <div class="role-alerts error">
                <ul class="list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group-role">
                <label>Nama Jabatan (Role Name)</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" required class="input-role">
            </div>

            <div class="permission-container">
                <h4>Detail Hak Akses (Permissions)</h4>

                @php $perms = $role->permissions ?? []; @endphp

                <div class="permission-group">
                    <strong>🚗 Modul Armada (Mobil)</strong>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="view_cars" {{ in_array('view_cars', $perms) ? 'checked' : '' }}> Lihat Data (Read)
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="create_cars" {{ in_array('create_cars', $perms) ? 'checked' : '' }}> Tambah (Create)
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="edit_cars" {{ in_array('edit_cars', $perms) ? 'checked' : '' }}> Edit (Update)
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="delete_cars" {{ in_array('delete_cars', $perms) ? 'checked' : '' }}> Hapus (Delete)
                    </label>
                </div>

                <div class="permission-group">
                    <strong>📂 Modul Booking & Denda</strong>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="view_bookings" {{ in_array('view_bookings', $perms) ? 'checked' : '' }}> Lihat Data Booking
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="edit_bookings" {{ in_array('edit_bookings', $perms) ? 'checked' : '' }}> Proses Status & Invoice
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="manage_fines" {{ in_array('manage_fines', $perms) ? 'checked' : '' }}> Input Denda/Kerusakan
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="delete_bookings" {{ in_array('delete_bookings', $perms) ? 'checked' : '' }}> Hapus Booking
                    </label>
                </div>

                <div class="permission-group">
                    <strong>📈 Modul Laporan</strong>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="view_reports" {{ in_array('view_reports', $perms) ? 'checked' : '' }}> Lihat & Export Laporan
                    </label>
                </div>
            </div>

            <div class="role-controls">
                <a href="{{ route('owner.roles.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection