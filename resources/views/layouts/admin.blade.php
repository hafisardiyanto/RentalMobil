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

            @php
                $isOwner = Auth::user()->role === 'owner';
                $permissions = Auth::user()->adminRole->permissions ?? [];
            @endphp

            <a href="{{ route('admin.dashboard') }}"
                class="nav-dashboard {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Dashboard</a>

            @if($isOwner || in_array('view_cars', $permissions))
                <a href="{{ route('admin.cars.index') }}"
                    class="nav-cars {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}">🚗 Manajemen Mobil</a>
                <a href="{{ route('facilities.index') }}"
                    class="nav-cars {{ request()->routeIs('facilities.*') ? 'active' : '' }}">✨ Master Fasilitas</a>
                <a href="{{ route('admin.maintenances.index') }}"
                    class="nav-cars {{ request()->routeIs('admin.maintenances.*') ? 'active' : '' }}">🔧 Perawatan Mobil</a>
            @endif

            @if($isOwner || in_array('view_bookings', $permissions))
                <a href="{{ route('admin.bookings.index') }}"
                    class="nav-bookings {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">📂 Manajemen
                    Booking</a>
                <a href="{{ route('admin.calendar.index') }}"
                    class="nav-bookings {{ request()->routeIs('admin.calendar.index') ? 'active' : '' }}">📅 Kalender
                    Sewa</a>
            @endif

            @if($isOwner || in_array('view_reports', $permissions))
                <a href="{{ route('admin.reports.index') }}"
                    class="nav-reports {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">📈 Laporan
                    Keuangan</a>
                <a href="{{ route('admin.reports.fleet') }}"
                    class="nav-reports {{ request()->routeIs('admin.reports.fleet') ? 'active' : '' }}">📊 Utilisasi & ROI
                    Mobil</a>
            @endif
        </div>

        <div class="nav-group">
            <div class="nav-title">Pengaturan</div>
            <a href="/">🌐 Lihat Web Panel</a>
            @if(Auth::user()->role === 'owner')
                <a href="{{ route('owner.admins.index') }}"
                    class="{{ request()->routeIs('owner.admins.*') ? 'active' : '' }}">
                    👥 Kelola Pegawai
                </a>
                <a href="{{ route('owner.roles.index') }}"
                    class="{{ request()->routeIs('owner.roles.*') ? 'active' : '' }}">
                    🛡️ Kelola Jabatan (Roles)
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="logout-link">🚪 Keluar (Logout)</a>
        </div>
    </aside>

    <div class="main-content">
        <header class="top-navbar">
            <div class="user-info">
                <span>Welcome, {{ Auth::user()->name }}</span>
                <span class="admin-badge">Admin</span>
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-box">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-box">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>

</html>