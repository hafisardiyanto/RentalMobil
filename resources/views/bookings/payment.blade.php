@extends('layouts.app')

@section('content')
    <style>
        .payment-container {
            padding: 5rem 5%;
            background: var(--background);
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .payment-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            width: 100%;
        }

        .info-summary {
            background: #F8FAFC;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
        }

        .info-label {
            color: #64748B;
            font-weight: 500;
        }

        .info-value {
            color: var(--secondary);
            font-weight: 700;
        }

        .file-upload-box {
            border: 2px dashed #CBD5E1;
            padding: 2rem;
            text-align: center;
            border-radius: 12px;
            background: #F8FAFC;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .file-upload-box:hover {
            border-color: var(--primary);
        }

        .preview-image {
            max-width: 100%;
            margin-top: 1rem;
            border-radius: 8px;
            display: none;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 2rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: #3b4be0;
        }
    </style>

    <div class="payment-container">
        <div class="payment-card">
            <h2 style="margin-bottom: 1.5rem; text-align: center;">Konfirmasi Pembayaran</h2>

            <div class="info-summary">
                <div class="info-row">
                    <span class="info-label">No. Booking</span>
                    <span class="info-value">{{ $booking->nomor_booking }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Mobil</span>
                    <span class="info-value">{{ $booking->car->brand }} {{ $booking->car->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Durasi</span>
                    <span class="info-value">{{ $booking->durasi }} Hari</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Tagihan</span>
                    <span class="info-value" style="color: var(--accent); font-size: 1.1rem;">Rp
                        {{ number_format($booking->total, 0, ',', '.') }}</span>
                </div>
                <p style="font-size: 0.85rem; color: #94A3B8; margin-top: 1rem; line-height: 1.4;">
                    Silakan transfer ke rekening <strong>BCA 1234567890 a.n RentalMobil</strong> sejumlah Total Tagihan
                    (Lunas) atau Deposit yang telah disepakati via WhatsApp.
                </p>
            </div>

            <form action="{{ route('bookings.payment.upload', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h4 style="margin-bottom: 1rem; color: var(--secondary);">Upload Bukti Transfer</h4>
                <div class="file-upload-box" onclick="document.getElementById('payment_proof').click()">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📸</div>
                    <div style="font-weight: 600; color: #64748B;">Klik untuk memilih foto dari galeri/folder</div>
                    <input type="file" name="payment_proof" id="payment_proof" style="display: none;" accept="image/*"
                        required onchange="previewFile()">
                </div>
                <img id="preview" class="preview-image" src="" alt="Preview Bukti Bayar">

                @error('payment_proof')
                    <div style="color: #EF4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
                @enderror

                <button type="submit" class="submit-btn">Kirim Bukti Pembayaran</button>
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