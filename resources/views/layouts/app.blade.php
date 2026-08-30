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
                @if(in_array(Auth::user()->role, ['admin', 'owner']))
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">Dashboard
                        {{ Auth::user()->role === 'owner' ? 'Owner' : 'Admin' }}</a>
                @else
                    <a href="{{ route('bookings.index') }}" class="nav-link-special">Riwayat Sewa</a>
                    <a href="{{ route('profile.index') }}" class="nav-link-special">Profil Saya</a>
                @endif
                <span class="nav-greeting">Halo, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="nav-form">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
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