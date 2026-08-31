@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/cars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title m-0">Tambah Perawatan: {{ $car->name }} ({{ $car->license_plate }})</h1>
    </div>

    <div class="box" style="padding: 2rem;">
        <form action="{{ route('admin.maintenances.store', $car->id) }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="type" class="form-label">Jenis Servis/Perawatan *</label>
                    <select name="type" id="type" class="form-control" required
                        style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #d1d5db;">
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

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
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

            <div class="alert alert-warning"
                style="background:#fffbeb; border: 1px solid #fde68a; color: #b45309; padding:1rem; border-radius:8px; margin-bottom: 2rem;">
                <strong>Perhatian:</strong> Dengan menyimpan data penyervisan ini, mobil akan otomatis berstatus
                <strong>"Maintenance"</strong> dan akan disembunyikan dari halaman depan (tidak bisa disewa) hingga Anda
                menandainya sebagai "Selesai".
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 600;">Simpan
                    Perawatan & Nonaktifkan Mobil</button>
                <a href="{{ route('admin.cars.show', $car->id) }}" class="btn"
                    style="padding: 0.75rem 2rem; background: #e5e7eb; color: #374151; text-decoration: none; border-radius: 8px; font-weight: 600;">Batal</a>
            </div>
        </form>
    </div>
@endsection