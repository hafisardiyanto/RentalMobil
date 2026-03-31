<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentalMobil - Sewa Mobil Premium</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @stack('styles')
</head>
<body>
    <nav>
        <div class="logo">RentalMobil</div>
        <div class="nav-links">
            <a href="/">Beranda</a>
            <a href="/#armada">Armada</a>
            <a href="/#tentang">Tentang</a>
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary" style="padding: 0.4rem 1rem;">Dashboard Admin</a>
                @else
                    <a href="{{ route('bookings.index') }}" class="nav-links a" style="color: var(--primary); font-weight: 600; margin-right: 1.5rem;">Riwayat Sewa</a>
                @endif
                <span style="color: var(--secondary); font-weight: 600;">Halo, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="padding: 0.4rem 1rem;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline" style="padding: 0.4rem 1rem;">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.4rem 1rem;">Daftar</a>
            @endauth
        </div>
    </nav>

    <main>
        @if (session('success'))
            <div class="alert alert-success animate-fade-in">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <p>&copy; 2026 RentalMobil. Hak Cipta Dilindungi.</p>
    </footer>
</body>
</html>
