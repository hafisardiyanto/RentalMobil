@extends('layouts.admin')

@section('content')
    <div class="box" style="max-width: 600px; margin: 0 auto;">
        <h2 style="margin-top: 0; margin-bottom: 1.5rem; color: #1e293b;">Pembuatan Jabatan & Hak Akses</h2>

        @if ($errors->any())
            <div style="background: #FEE2E2; color: #B91C1C; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.roles.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Jabatan (Role Name)</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="Contoh: Manajer Operasional, Kasir, Admin Kendaraan"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div
                style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1.25rem; border-radius: 8px; margin-bottom: 2rem;">
                <h4 style="margin-top: 0; margin-bottom: 1rem; color: #334155;">Pilih Modul / Hak Akses (Permissions)</h4>

                <label style="display: flex; align-items: center; margin-bottom: 0.75rem; cursor: pointer;">
                    <input type="checkbox" name="permissions[]" value="manage_cars"
                        style="margin-right: 10px; width: 1.2rem; height: 1.2rem;">
                    <span style="font-weight: 500;">🚗 Manajemen Mobil Armada (CRUD Kendaraan)</span>
                </label>

                <label style="display: flex; align-items: center; margin-bottom: 0.75rem; cursor: pointer;">
                    <input type="checkbox" name="permissions[]" value="manage_bookings"
                        style="margin-right: 10px; width: 1.2rem; height: 1.2rem;">
                    <span style="font-weight: 500;">📂 Manajemen Booking & Denda (Check-in/Return)</span>
                </label>

                <label style="display: flex; align-items: center; margin-bottom: 0.75rem; cursor: pointer;">
                    <input type="checkbox" name="permissions[]" value="view_reports"
                        style="margin-right: 10px; width: 1.2rem; height: 1.2rem;">
                    <span style="font-weight: 500;">📈 Laporan Keuangan (View & Export Income)</span>
                </label>
                <p style="margin-top: 1rem; margin-bottom: 0; font-size: 0.85rem; color: #64748B;">Catatan: Jabatan tanpa
                    ceklis hanya bisa melihat halaman Dashboard.</p>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('owner.roles.index') }}" class="btn btn-outline"
                    style="padding: 0.75rem 1.5rem; text-decoration: none; border: 1px solid #ccc; color: #475569;">Batal</a>
                <button type="submit" class="btn btn-primary"
                    style="padding: 0.75rem 1.5rem; border: none; cursor: pointer;">Simpan Jabatan Baru</button>
            </div>
        </form>
    </div>
@endsection