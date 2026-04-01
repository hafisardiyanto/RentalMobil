@extends('layouts.app')

@section('content')
<style>
    .auth-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: #F1F5F9;
    }
    .auth-card {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 450px;
    }
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1E293B; }
    .form-control {
        width: 100%; padding: 1rem; border: 2px solid #E2E8F0; border-radius: 10px; font-size: 1rem;
    }
    .form-control:focus { border-color: var(--primary); outline: none; }
    .btn-primary {
        width: 100%; padding: 1rem; border: none; border-radius: 10px; background: var(--primary);
        color: white; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s;
    }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
</style>

<div class="auth-container">
    <div class="auth-card">
        <h2 style="margin-bottom: 1rem; font-weight: 800; color: #1E293B;">Lupa Password? 🔑</h2>
        <p style="color: #64748B; margin-bottom: 2rem; line-height: 1.6;">
            Masukkan alamat email akun Anda. Kami akan mengirimkan **identitas password baru** langsung ke nomor WhatsApp yang terdaftar.
        </p>

        @if(session('success'))
            <div style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 600;">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="Masukkan email terdaftar" autofocus>
                @error('email')
                    <span style="color: #EF4444; font-size: 0.85rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-primary">Kirim Password ke WA</button>

            <div style="margin-top: 1.5rem; text-align: center; font-size: 0.9rem;">
                <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Kembali ke Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
