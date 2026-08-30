@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
    <div class="register-success-container">
        <div class="rs-card">
            <div class="rs-check-icon">✓</div>
            <h1 class="rs-title">Registrasi Berhasil!</h1>
            <p class="rs-subtitle">Halo <strong>{{ session('success_name') }}</strong>, akun Anda telah siap digunakan.</p>

            <div class="rs-credentials-box">
                <div class="rs-credential-item">
                    <span class="rs-credential-label">Email:</span>
                    <span class="rs-credential-value">{{ session('success_email') }}</span>
                </div>
                <div class="rs-credential-item">
                    <span class="rs-credential-label">Password:</span>
                    <span class="rs-credential-value">{{ session('success_password') }}</span>
                </div>
                <div class="rs-note-alert">
                    ⚠ Mohon catat atau simpan password Anda sebelum melanjutkan.
                </div>
            </div>

            <a href="{{ route('login') }}" class="rs-btn-login">Login Sekarang</a>

            <p class="rs-privacy-notice">
                Demi privasi, detail akun ini tidak dikirimkan ke WhatsApp pemilik rental. <br>
                Halaman ini hanya muncul sekali.
            </p>
        </div>
    </div>
@endsection