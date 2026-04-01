@extends('layouts.app')

@section('content')
<style>
    .register-success-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: #F8FAFC;
    }

    .success-card {
        background: white;
        padding: 3.5rem;
        border-radius: 28px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        text-align: center;
        max-width: 550px;
        width: 100%;
        border: 1px solid #E2E8F0;
        position: relative;
        overflow: hidden;
    }

    .success-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, #4F46E5, #06B6D4);
    }

    .check-icon {
        width: 72px;
        height: 72px;
        background: #DEF7EC;
        color: #046C4E;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
    }

    .credentials-box {
        background: #F1F5F9;
        border-radius: 16px;
        padding: 1.5rem;
        margin: 2rem 0;
        text-align: left;
        border: 1px solid #E2E8F0;
    }

    .credential-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .credential-item:last-child {
        margin-bottom: 0;
    }

    .credential-label {
        color: #64748B;
        font-weight: 500;
    }

    .credential-value {
        color: #1E293B;
        font-weight: 700;
    }

    .btn-login {
        display: block;
        width: 100%;
        padding: 1rem;
        background: #1E293B;
        color: white;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s;
        margin-top: 1rem;
    }

    .btn-login:hover {
        background: #334155;
        transform: translateY(-2px);
    }

    .privacy-notice {
        font-size: 0.8rem;
        color: #94A3B8;
        margin-top: 1.5rem;
        line-height: 1.4;
    }
</style>

<div class="register-success-container">
    <div class="success-card">
        <div class="check-icon">✓</div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: #1E293B; margin-bottom: 0.5rem;">Registrasi Berhasil!</h1>
        <p style="color: #64748B;">Halo <strong>{{ session('success_name') }}</strong>, akun Anda telah siap digunakan.</p>

        <div class="credentials-box">
            <div class="credential-item">
                <span class="credential-label">Email:</span>
                <span class="credential-value">{{ session('success_email') }}</span>
            </div>
            <div class="credential-item">
                <span class="credential-label">Password:</span>
                <span class="credential-value">{{ session('success_password') }}</span>
            </div>
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #E2E8F0; font-size: 0.85rem; color: #DC2626; font-weight: 600;">
                ⚠ Mohon catat atau simpan password Anda sebelum melanjutkan.
            </div>
        </div>

        <a href="{{ route('login') }}" class="btn-login">Login Sekarang</a>

        <p class="privacy-notice">
            Demi privasi, detail akun ini tidak dikirimkan ke WhatsApp pemilik rental. <br>
            Halaman ini hanya muncul sekali.
        </p>
    </div>
</div>
@endsection
