@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <h2 class="auth-title-emoji">Lupa Password? 🔑</h2>
            <p class="auth-subtitle">
                Masukkan alamat email akun Anda. Kami akan mengirimkan **link untuk atur ulang password** ke email Anda.
                Setelah berhasil diubah, password baru akan dikonfirmasi via WhatsApp.
            </p>

            @if(session('success'))
                <div class="auth-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="auth-alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required
                        placeholder="Masukkan email terdaftar" autofocus>
                    @error('email')
                        <span class="auth-input-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary auth-btn">Kirim Link Reset Password</button>

                <div class="auth-back-link">
                    <a href="{{ route('login') }}">Kembali ke Login</a>
                </div>
            </form>
        </div>
    </div>
@endsection