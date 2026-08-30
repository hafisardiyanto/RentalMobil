@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/owner/roles.css') }}">
@endpush

@section('content')
    <div class="box role-form-box">
        <h2 class="role-page-title">Pembuatan Jabatan & Hak Akses</h2>

        @if ($errors->any())
            <div class="role-alerts error">
                <ul class="list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.roles.store') }}" method="POST">
            @csrf

            <div class="form-group-role">
                <label>Nama Jabatan (Role Name)</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="Contoh: Manajer Operasional, Kasir, Admin Kendaraan" class="input-role">
            </div>

            <div class="permission-container">
                <h4>Pilih Modul / Hak Akses (Permissions)</h4>

                <div class="permission-group">
                    <strong>🚗 Modul Armada (Mobil)</strong>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="view_cars"> Lihat Data (Read)
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="create_cars"> Tambah (Create)
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="edit_cars"> Edit (Update)
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="delete_cars"> Hapus (Delete)
                    </label>
                </div>

                <div class="permission-group">
                    <strong>📂 Modul Booking & Denda</strong>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="view_bookings"> Lihat Data Booking
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="edit_bookings"> Proses Status & Invoice
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="manage_fines"> Input Denda/Kerusakan
                    </label>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="delete_bookings"> Hapus Booking
                    </label>
                </div>

                <div class="permission-group">
                    <strong>📈 Modul Laporan</strong>
                    <label class="permission-label">
                        <input type="checkbox" name="permissions[]" value="view_reports"> Lihat & Export Laporan
                    </label>
                </div>

                <p style="margin-top: 1rem; margin-bottom: 0; font-size: 0.85rem; color: #64748B;">Catatan: Jabatan tanpa
                    ceklis hanya bisa melihat halaman Dashboard.</p>
            </div>

            <div class="role-controls">
                <a href="{{ route('owner.roles.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary btn-save">Simpan Jabatan Baru</button>
            </div>
        </form>
    </div>
@endsection