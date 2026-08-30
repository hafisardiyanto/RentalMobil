@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/bookings.css') }}">
@endpush

@section('content')
    <div class="box return-container">
        <h2>Form Pengembalian Kendaraan (Return)</h2>
        <p>Booking ID: {{ $booking->nomor_booking }} | Mobil: {{ $booking->car->brand }} {{ $booking->car->name }}</p>
        <div class="return-info-box">
            <strong>Data Saat Berangkat:</strong><br>
            KM Awal: {{ $booking->km_awal ?? '-' }} | BBM Awal: {{ $booking->bbm_awal ?? '-' }}<br>
            Kondisi Berangkat: {{ $booking->kondisi_awal ?: 'Tidak ada catatan' }}
        </div>

        <form action="{{ route('admin.bookings.process-return', $booking->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="return-form-flex">
                <div class="return-form-group">
                    <label for="km_akhir" class="b-label">KM Akhir (Saat
                        PK)</label>
                    <input type="number" name="km_akhir" id="km_akhir" class="return-input" required>
                </div>

                <div class="return-form-group">
                    <label for="bbm_akhir" class="b-label">Kondisi BBM Akhir</label>
                    <select name="bbm_akhir" id="bbm_akhir" class="return-input" required>
                        <option value="Full">Full</option>
                        <option value="3/4">3/4</option>
                        <option value="1/2">1/2</option>
                        <option value="1/4">1/4</option>
                        <option value="Empty (E)">Empty (E)</option>
                    </select>
                </div>
            </div>

            <div class="b-form-group">
                <label for="kondisi_akhir" class="b-label">Catatan Kondisi Akhir (Lecet/Minus Baru)</label>
                <textarea name="kondisi_akhir" id="kondisi_akhir" rows="3" class="return-input"></textarea>
            </div>

            <div class="return-penalty-box">
                <div class="form-group" style="flex: 1;">
                    <label for="denda_terlambat" class="return-danger-label">Denda Keterlambatan (Rp)</label>
                    <input type="number" name="denda_terlambat" id="denda_terlambat" value="0" class="return-danger-input">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="biaya_kerusakan" class="return-danger-label">Biaya Kerusakan (Rp)</label>
                    <input type="number" name="biaya_kerusakan" id="biaya_kerusakan" value="0" class="return-danger-input">
                </div>
            </div>

            <div class="return-form-group">
                <label for="foto_akhir" class="b-label">Foto Bukti Kendaraan Kembali (Optional)</label>
                <input type="file" name="foto_akhir" id="foto_akhir" accept="image/*" class="return-dashed-input">
            </div>

            <button type="submit" class="btn-return-lg">Selesaikan Rental (Status Selesai)</button>
        </form>
    </div>
@endsection