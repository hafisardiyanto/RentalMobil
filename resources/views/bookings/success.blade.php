@extends('layouts.app')

@section('content')
<style>
    .success-page {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: #F8FAFC;
    }

    .success-card {
        background: white;
        padding: 3rem;
        border-radius: 24px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        text-align: center;
        max-width: 500px;
        width: 100%;
        border: 1px solid #E2E8F0;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: #D1FAE5;
        color: #10B981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 1.5rem;
        animation: scaleIn 0.5s ease-out;
    }

    @keyframes scaleIn {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .success-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1E293B;
        margin-bottom: 1rem;
    }

    .success-msg {
        color: #64748B;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .timer-container {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #F1F5F9;
    }

    .progress-bar {
        width: 100%;
        height: 6px;
        background: #F1F5F9;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .progress-fill {
        height: 100%;
        background: var(--primary);
        width: 0%;
        transition: width 3s linear;
    }

    .wa-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: #25D366;
        color: white;
        padding: 1rem 2rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s;
        box-shadow: 0 10px 15px -3px rgba(37, 211, 102, 0.3);
    }

    .wa-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(37, 211, 102, 0.4);
    }
</style>

<div class="success-page">
    <div class="success-card">
        <div class="success-icon">✓</div>
        <h1 class="success-title">Booking Berhasil!</h1>
        <p class="success-msg">
            ID Pesanan Anda: <strong>#{{ $booking->id }}</strong><br>
            Data Anda telah tersimpan di sistem kami. Sekarang Anda akan diarahkan ke WhatsApp untuk proses konfirmasi pembayaran.
        </p>

        <a href="{{ $waUrl }}" id="waLink" class="wa-btn">
            <span>💬 Hubungi Admin via WA</span>
        </a>

        <div class="timer-container">
            <div class="progress-bar">
                <div id="progressFill" class="progress-fill"></div>
            </div>
            <p id="countdownText" style="font-size: 0.9rem; color: #94A3B8;">
                Membuka WhatsApp secara otomatis dalam 3 detik...
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
