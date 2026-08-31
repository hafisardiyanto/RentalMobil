@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer-booking.css') }}">
@endpush

@section('content')
    <div class="payment-container">
        <div class="payment-card">
            <h2 class="pt-title">Konfirmasi Pembayaran</h2>

            <div class="info-summary-cs">
                <div class="info-row-cs">
                    <span class="info-label-cs">No. Booking</span>
                    <span class="info-value-cs">{{ $booking->nomor_booking }}</span>
                </div>
                <div class="info-row-cs">
                    <span class="info-label-cs">Mobil</span>
                    <span class="info-value-cs">{{ $booking->car->brand }} {{ $booking->car->name }}</span>
                </div>
                <div class="info-row-cs">
                    <span class="info-label-cs">Durasi</span>
                    <span class="info-value-cs">{{ $booking->durasi }} Hari</span>
                </div>
                <div class="info-row-cs">
                    <span class="info-label-cs">Total Tagihan</span>
                    <span class="info-value-cs pt-accent">Rp
                        {{ number_format($booking->total, 0, ',', '.') }}</span>
                </div>
                <p class="pt-desc">
                    Silakan transfer ke rekening <strong>BCA 1234567890 a.n RentalMobil</strong> sejumlah Total Tagihan
                    (Lunas) atau Deposit yang telah disepakati via WhatsApp.
                </p>
            </div>

            <form action="{{ route('bookings.payment.upload', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom: 1.5rem; text-align: left;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #475569;">Jenis Pembayaran
                        *</label>
                    <select name="type" required
                        style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px;">
                        <option value="DP">Uang Muka (DP)</option>
                        <option value="Pelunasan">Pelunasan Total & Tagihan</option>
                        <option value="Deposit">Deposit Jaminan Sewa</option>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem; text-align: left;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #475569;">Nominal Transfer
                        (Rp) *</label>
                    <input type="number" name="amount" required min="1" placeholder="Cth: 500000"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>

                <h4 class="pt-subtitle">Upload Bukti Transfer *</h4>
                <div class="file-upload-box" onclick="document.getElementById('payment_proof').click()">
                    <div class="pt-emoji">📸</div>
                    <div class="pt-hint">Klik untuk memilih foto dari galeri/folder</div>
                    <input type="file" name="payment_proof" id="payment_proof" class="pt-d-none" accept="image/*" required
                        onchange="previewFile()">
                </div>
                <img id="preview" class="preview-image" src="" alt="Preview Bukti Bayar">

                @error('payment_proof')
                    <div class="pt-error">{{ $message }}</div>
                @enderror

                <button type="submit" class="submit-btn-cs">Kirim Bukti Pembayaran</button>
            </form>
        </div>
    </div>

    <script>
        function previewFile() {
            const preview = document.getElementById('preview');
            const file = document.getElementById('payment_proof').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                preview.src = reader.result;
                preview.style.display = 'block';
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection