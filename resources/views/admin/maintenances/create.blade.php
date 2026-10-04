@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/cars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/maintenance.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title m-0">Tambah Perawatan: {{ $car->name }} ({{ $car->license_plate }})</h1>
    </div>

    <div class="box" style="padding: 2rem;">
        <form action="{{ route('admin.maintenances.store', $car->id) }}" method="POST">
            @csrf

            <div class="maint-grid">
                <div class="form-group">
                    <label for="type" class="form-label">Jenis Servis/Perawatan *</label>
                    <select name="type" id="type" class="form-control maint-select" required>
                        <option value="Servis Rutin">Servis Rutin (Ganti Oli dsb)</option>
                        <option value="Perbaikan Mesin">Perbaikan Mesin</option>
                        <option value="Perbaikan Bodi">Perbaikan Body / Cat</option>
                        <option value="Ganti Ban">Ganti Ban</option>
                        <option value="Pajak Tahunan">Pajak Tahunan / STNK</option>
                        <option value="Cuci/Salon">Cuci / Salon Mobil</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="service_date" class="form-label">Tanggal Masuk Servis *</label>
                    <input type="date" name="service_date" id="service_date" class="form-control" required
                        value="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label for="cost" class="form-label">Estimasi/Total Biaya (Rp) *</label>
                    <input type="number" name="cost" id="cost" class="form-control" min="0" required
                        placeholder="Contoh: 1500000">
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Catatan Detail (Opsional)</label>
                    <input type="text" name="description" id="description" class="form-control"
                        placeholder="Contoh: Bengkel resmi Ahass, ganti busi">
                </div>
            </div>

            <hr style="border:0; border-top:1px solid #e5e7eb; margin: 2rem 0;">
            <h4 style="margin-top:0; margin-bottom:1rem; color:#4b5563;">Pencatatan Kilometer (Opsional tapi Disarankan)
            </h4>

            <div class="maint-grid">
                <div class="form-group">
                    <label for="last_km" class="form-label">Kilometer Saat Ini (KM)</label>
                    <input type="number" name="last_km" id="last_km" class="form-control" placeholder="Contoh: 59800"
                        min="0">
                </div>
                <div class="form-group">
                    <label for="next_km" class="form-label">Target Servis Berikutnya (KM)</label>
                    <input type="number" name="next_km" id="next_km" class="form-control" placeholder="Contoh: 65000"
                        min="0">
                </div>
            </div>

            <div class="alert alert-warning maint-alert-warning">
                <strong>Perhatian:</strong> Dengan menyimpan data penyervisan ini, mobil akan otomatis berstatus
                <strong>"Maintenance"</strong> dan akan disembunyikan dari halaman depan (tidak bisa disewa) hingga Anda
                menandainya sebagai "Selesai".
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary maint-btn-save">Simpan
                    Perawatan & Nonaktifkan Mobil</button>
                <a href="{{ route('admin.cars.show', $car->id) }}" class="btn maint-btn-cancel">Batal</a>
            </div>
        </form>
    </div>
@endsection