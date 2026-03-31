<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RentalMobil</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            background-color: #F1F5F9;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--secondary);
            color: white;
            padding-top: 2rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
            z-index: 1000;
        }

        .sidebar .brand {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2.5rem;
            padding: 0 1.5rem;
            color: #E2E8F0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar a {
            display: block;
            padding: 1rem 1.5rem;
            color: #CBD5E1;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            border-left: 3px solid transparent;
        }

        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.05);
            color: white;
            border-left-color: var(--primary);
        }

        .sidebar .nav-group {
            margin-bottom: 2rem;
        }

        .sidebar .nav-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748B;
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            height: 70px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .top-navbar .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 500;
        }

        .content-area {
            padding: 2rem;
            flex: 1;
        }

        .page-title {
            font-size: 1.8rem;
            color: var(--text-main);
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        /* Tables & Cards Base */
        .box {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #E2E8F0;
        }
        
    </style>
    @stack('admin_styles')
</head>
<body>

    <aside class="sidebar">
        <div class="brand">
            🚙 Admin Panel
        </div>

        <div class="nav-group">
            <div class="nav-title">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Dashboard</a>
            <a href="{{ route('admin.cars.index') }}" class="{{ request()->routeIs('admin.cars.*') ? 'active' : '' }}">🚗 Manajemen Mobil</a>
            <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">📂 Manajemen Booking</a>
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
