@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/bookings.css') }}">
@endpush

@section('content')
    <div class="box return-container">
        <h2>Form Serah Terima Kendaraan (Handover)</h2>
        <p>Booking ID: {{ $booking->nomor_booking }} | Mobil: {{ $booking->car->brand }} {{ $booking->car->name }}</p>
        <hr class="handover-divider">

        <form action="{{ route('admin.bookings.process-handover', $booking->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="return-form-group">
                <label for="km_awal" class="return-danger-label" style="color:var(--text-main);">KM Awal (Saat
                    Diserahkan)</label>
                <input type="number" name="km_awal" id="km_awal" class="return-input" placeholder="Contoh: 120500" required>
            </div>

            <div class="return-form-group">
                <label for="bbm_awal" class="return-danger-label" style="color:var(--text-main);">Kondisi BBM
                    Awal</label>
                <select name="bbm_awal" id="bbm_awal" class="return-input" required>
                    <option value="Full">Full</option>
                    <option value="3/4">3/4</option>
                    <option value="1/2">1/2</option>
                    <option value="1/4">1/4</option>
                    <option value="Empty (E)">Empty (E)</option>
                </select>
            </div>

            <div class="return-form-group">
                <label for="kondisi_awal" class="return-danger-label" style="color:var(--text-main);">Catatan Kondisi
                    Fisik (Lecet/Minus)</label>
                <textarea name="kondisi_awal" id="kondisi_awal" rows="4" class="return-input"
                    placeholder="Catat jika ada lecet di body..."></textarea>
            </div>

            <div class="return-form-group">
                <label for="foto_awal" class="return-danger-label" style="color:var(--text-main);">Foto Bukti
                    Kendaraan Berangkat (Optional)</label>
                <input type="file" name="foto_awal" id="foto_awal" accept="image/*" class="return-dashed-input">
            </div>

            <button type="submit" class="btn-return-lg">Proses
                Serah Terima & Ubah ke 'Sedang Disewa'</button>
        </form>
    </div>
@endsection