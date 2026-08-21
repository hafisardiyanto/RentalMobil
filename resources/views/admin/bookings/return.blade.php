@extends('layouts.admin')

@section('content')
    <div class="box" style="max-width: 600px; margin: 0 auto; padding: 2rem;">
        <h2>Form Pengembalian Kendaraan (Return)</h2>
        <p>Booking ID: {{ $booking->nomor_booking }} | Mobil: {{ $booking->car->brand }} {{ $booking->car->name }}</p>
        <div style="background: #F1F5F9; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <strong>Data Saat Berangkat:</strong><br>
            KM Awal: {{ $booking->km_awal ?? '-' }} | BBM Awal: {{ $booking->bbm_awal ?? '-' }}<br>
            Kondisi Berangkat: {{ $booking->kondisi_awal ?: 'Tidak ada catatan' }}
        </div>

        <form action="{{ route('admin.bookings.process-return', $booking->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div style="display: flex; gap: 1rem;">
                <div class="form-group" style="flex: 1; margin-bottom: 1rem;">
                    <label for="km_akhir" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">KM Akhir (Saat
                        PK)</label>
                    <input type="number" name="km_akhir" id="km_akhir"
                        style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 8px;" required>
                </div>

                <div class="form-group" style="flex: 1; margin-bottom: 1rem;">
                    <label for="bbm_akhir" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Kondisi BBM
                        Akhir</label>
                    <select name="bbm_akhir" id="bbm_akhir"
                        style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 8px;" required>
                        <option value="Full">Full</option>
                        <option value="3/4">3/4</option>
                        <option value="1/2">1/2</option>
                        <option value="1/4">1/4</option>
                        <option value="Empty (E)">Empty (E)</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="kondisi_akhir" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Catatan Kondisi
                    Akhir (Lecet/Minus Baru)</label>
                <textarea name="kondisi_akhir" id="kondisi_akhir" rows="3"
                    style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 8px;"></textarea>
            </div>

            <div
                style="display: flex; gap: 1rem; background: #FEF2F2; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <div class="form-group" style="flex: 1;">
                    <label for="denda_terlambat"
                        style="display: block; font-weight: bold; color: #991B1B; margin-bottom: 0.5rem;">Denda
                        Keterlambatan (Rp)</label>
                    <input type="number" name="denda_terlambat" id="denda_terlambat" value="0"
                        style="width: 100%; padding: 0.8rem; border: 1px solid #FCA5A5; border-radius: 8px;">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="biaya_kerusakan"
                        style="display: block; font-weight: bold; color: #991B1B; margin-bottom: 0.5rem;">Biaya Kerusakan
                        (Rp)</label>
                    <input type="number" name="biaya_kerusakan" id="biaya_kerusakan" value="0"
                        style="width: 100%; padding: 0.8rem; border: 1px solid #FCA5A5; border-radius: 8px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="foto_akhir" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Foto Bukti
                    Kendaraan Kembali (Optional)</label>
                <input type="file" name="foto_akhir" id="foto_akhir" accept="image/*"
                    style="width: 100%; padding: 0.8rem; border: 1px dashed #CBD5E1; border-radius: 8px; background: #F8FAFC;">
            </div>

            <button type="submit"
                style="background: #059669; color: white; padding: 1rem; width: 100%; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Selesaikan
                Rental (Status Selesai)</button>
        </form>
    </div>
@endsection