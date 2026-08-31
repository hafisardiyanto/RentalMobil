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
            <a href="/armada">Armada</a>
            <a href="/#cara-sewa">Cara Sewa</a>
            <a href="/#tentang">Tentang</a>
            <a href="/#faq">FAQ</a>
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

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/62857a48174062?text=Halo,%20saya%20butuh%20bantuan%20mengenai%20sewa%20mobil."
        class="floating-wa" target="_blank"
        style="position: fixed; bottom: 30px; right: 30px; background-color: #25D366; color: white; border-radius: 50Px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(37, 211, 102, 0.4); z-index: 1000; transition: transform 0.3s ease;">
        <svg width="35" height="35" viewBox="0 0 24 24" fill="#ffffff" stroke="#ffffff" stroke-width="0">
            <path
                d="M12.01 2.01c-5.46 0-9.89 4.43-9.89 9.89 0 1.74.45 3.4 1.3 4.88L2 22l5.44-1.42c1.44.78 3.06 1.22 4.57 1.22 5.46 0 9.89-4.43 9.89-9.89 0-5.46-4.43-9.89-9.89-9.89zM12.01 19.98c-1.47 0-2.91-.39-4.18-1.14l-.3-.18-3.1.81.82-3.03-.2-.31c-.82-1.28-1.25-2.76-1.25-4.28 0-4.46 3.63-8.08 8.08-8.08 4.46 0 8.08 3.63 8.08 8.08s-3.63 8.08-8.08 8.08zm4.43-6.07c-.24-.12-1.44-.71-1.66-.79-.22-.08-.38-.12-.54.12-.16.24-.62.79-.76.95-.14.16-.28.18-.52.06-1.25-.63-2.39-1.42-3.19-2.35-.22-.26.01-.24.23-.68.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.19-.47-.38-.41-.54-.41-.16 0-.34-.02-.5-.02s-.42.06-.64.3c-.22.24-.84.82-.84 2.01 0 1.19.86 2.34.98 2.5.12.16 1.7 2.61 4.14 3.65.58.25 1.03.4 1.38.51.58.19 1.12.16 1.54.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.22-.16-.46-.28z" />
        </svg>
    </a>

    <style>
        .floating-wa:hover {
            transform: scale(1.1) translateY(-5Px);
        }
    </style>
</body>

</html>