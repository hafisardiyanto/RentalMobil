@extends('layouts.admin')

@section('content')
    <div class="box" style="max-width: 600px; margin: 0 auto; padding: 2rem;">
        <h2>Form Serah Terima Kendaraan (Handover)</h2>
        <p>Booking ID: {{ $booking->nomor_booking }} | Mobil: {{ $booking->car->brand }} {{ $booking->car->name }}</p>
        <hr style="margin: 1rem 0;">

        <form action="{{ route('admin.bookings.process-handover', $booking->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="km_awal" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">KM Awal (Saat
                    Diserahkan)</label>
                <input type="number" name="km_awal" id="km_awal"
                    style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 8px;"
                    placeholder="Contoh: 120500" required>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="bbm_awal" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Kondisi BBM
                    Awal</label>
                <select name="bbm_awal" id="bbm_awal"
                    style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 8px;" required>
                    <option value="Full">Full</option>
                    <option value="3/4">3/4</option>
                    <option value="1/2">1/2</option>
                    <option value="1/4">1/4</option>
                    <option value="Empty (E)">Empty (E)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="kondisi_awal" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Catatan Kondisi
                    Fisik (Lecet/Minus)</label>
                <textarea name="kondisi_awal" id="kondisi_awal" rows="4"
                    style="width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 8px;"
                    placeholder="Catat jika ada lecet di body..."></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="foto_awal" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Foto Bukti
                    Kendaraan Berangkat (Optional)</label>
                <input type="file" name="foto_awal" id="foto_awal" accept="image/*"
                    style="width: 100%; padding: 0.8rem; border: 1px dashed #CBD5E1; border-radius: 8px; background: #F8FAFC;">
            </div>

            <button type="submit"
                style="background: var(--primary); color: white; padding: 1rem; width: 100%; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Proses
                Serah Terima & Ubah ke 'Sedang Disewa'</button>
        </form>
    </div>
@endsection