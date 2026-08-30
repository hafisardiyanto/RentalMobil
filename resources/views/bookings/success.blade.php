@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer-booking.css') }}">
@endpush

@section('content')

    <div class="success-page">
        <div class="success-card">
            <div class="success-icon">✓</div>
            <h1 class="success-title">Booking Berhasil!</h1>
            <p class="success-msg">
                ID Pesanan Anda: <strong>#{{ $booking->id }}</strong><br>
                Data Anda telah tersimpan di sistem kami. Sekarang Anda akan diarahkan ke WhatsApp untuk proses konfirmasi
                pembayaran.
            </p>

            <a href="{{ $waUrl }}" id="waLink" class="wa-btn">
                <span>💬 Hubungi Admin via WA</span>
            </a>

            <div class="timer-container">
                <div class="progress-bar">
                    <div id="progressFill" class="progress-fill"></div>
                </div>
                <p id="countdownText" class="s-text">
                    Membuka WhatsApp secara otomatis dalam 3 detik...
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const progressFill = document.getElementById('progressFill');
            const countdownText = document.getElementById('countdownText');
            const waLink = document.getElementById('waLink').href;

            // Mulai animasi progress bar
            setTimeout(() => {
                progressFill.style.width = '100%';
            }, 100);

            let seconds = 3;
            const interval = setInterval(() => {
                seconds--;
                if (seconds > 0) {
                    countdownText.innerText = `Membuka WhatsApp secara otomatis dalam ${seconds} detik...`;
                } else {
                    countdownText.innerText = 'Membuka WhatsApp...';
                    clearInterval(interval);
                    window.location.href = waLink;
                }
            }, 1000);
        });
    </script>
@endsection