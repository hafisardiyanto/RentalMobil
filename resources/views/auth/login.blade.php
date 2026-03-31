@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2>Selamat Datang Kembali</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="contoh@email.com">
                @error('email')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Masukkan kata sandi">
                @error('password')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary auth-btn" style="margin-bottom: 1rem;">Masuk</button>

            <!-- Google Login Button -->
            <a href="{{ route('auth.google') }}" class="btn btn-outline" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; border: 1px solid #E2E8F0; text-decoration: none; color: #374151; padding: 0.8rem; border-radius: 8px; font-weight: 500; font-size: 0.95rem; background: #fff; transition: all 0.3s ease;">
                <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" width="20" alt="Google">
                Masuk dengan Google
            </a>

            <div class="auth-links">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
            </div>
        </form>
    </div>
</div>
@endsection
