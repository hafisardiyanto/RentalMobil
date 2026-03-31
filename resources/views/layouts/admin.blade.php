<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RentalMobil</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/layout.css') }}">
    @stack('admin_styles')
</head>
<body>

    <aside class="sidebar">
        <div class="brand">
            🚙 Admin Panel
        </div>

        <div class="nav-group">
            <div class="nav-title">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-dashboard {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Dashboard</a>
            <a href="{{ route('admin.cars.index') }}" class="nav-cars {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}">🚗 Manajemen Mobil</a>
            <a href="{{ route('admin.bookings.index') }}" class="nav-bookings {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">📂 Manajemen Booking</a>
        </div>
        
        <div class="nav-group">
            <div class="nav-title">Pengaturan</div>
            <a href="/">🌐 Lihat Web Panel</a>
            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #FCA5A5;">🚪 Keluar (Logout)</a>
        </div>
    </aside>

    <div class="main-content">
        <header class="top-navbar">
            <div class="user-info">
                <span>Welcome, {{ Auth::user()->name }}</span>
                <span style="background: var(--primary); color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">Admin</span>
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div class="alert alert-success" style="margin: 0 0 1.5rem 0;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger" style="margin: 0 0 1.5rem 0;">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
