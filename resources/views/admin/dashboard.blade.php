@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')

<h1 class="page-title">Dashboard Analitik</h1>

<div class="grid-dashboard">
    <div class="stat-card">
        <div class="stat-title">Total Pendapatan (Revenue)</div>
        <div class="stat-value text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div style="margin-top: 1rem; font-size: 0.85rem; color: #64748B;">Dari seluruh transaksi sukses.</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-title">Mobil Sedang Jalan</div>
        <div class="stat-value text-primary">{{ $activeBookings->count() }} <span style="font-size: 1rem; font-weight: 500;">Unit</span></div>
        <div style="margin-top: 1rem; font-size: 0.85rem; color: #64748B;">Lagi disewa per hari ini.</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-title">Reservasi Mendatang</div>
        <div class="stat-value" style="color: #F59E0B;">{{ $upcomingBookings->count() }}</div>
        <div style="margin-top: 1rem; font-size: 0.85rem; color: #64748B;">Jadwal peminjaman antre.</div>
    </div>
</div>

<div class="panel-grid">
    <!-- Kolom Kiri -->
    <div class="panel">
        <h3 class="panel-header">🚦 Status Mobil Real-time (Sedang Jalan/Disewa)</h3>
        @if($activeBookings->count() > 0)
            <table class="table-container">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Mobil</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeBookings as $booking)
                    <tr>
                        <td style="font-weight: 600;">{{ $booking->user->name }}</td>
                        <td>{{ $booking->car->brand }} {{ $booking->car->name }}</td>
                        <td style="color: #EF4444; font-weight: 600;">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</td>
                        <td><span class="badge badge-active">Sedang Jalan</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 2rem; color: #94A3B8;">
                💤 Tidak ada mobil yang sedang berjalan hari ini.
            </div>
        @endif
        
        <h3 class="panel-header" style="margin-top: 3rem;">📅 Booking / Reservasi Mendatang</h3>
        @if($upcomingBookings->count() > 0)
            <table class="table-container">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Mobil</th>
                        <th>Tgl Mulai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcomingBookings as $booking)
                    <tr>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->car->brand }} {{ $booking->car->name }}</td>
                        <td style="color: #3B82F6; font-weight: 600;">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 2rem; color: #94A3B8;">
                🗓️ Belum ada daftar pemesanan di masa depan.
            </div>
        @endif
    </div>

    <!-- Kolom Kanan -->
    <div class="panel">
        <h3 class="panel-header">📈 Mobil Paling Laku (Top 5)</h3>
        <div style="display: flex; flex-direction: column;">
            @forelse($frequentCars as $car)
                <div class="list-item">
                    <div class="car-info">
                        <strong>{{ $car->name }}</strong>
                        <span>{{ $car->brand }} &bull; Plat: {{ $car->license_plate }}</span>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">{{ $car->bookings_count }}</span>
                        <span style="font-size: 0.8rem; color: #64748B; display: block;">x disewa</span>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 2rem; color: #94A3B8;">
                    Belum ada data riwayat persewaan.
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
