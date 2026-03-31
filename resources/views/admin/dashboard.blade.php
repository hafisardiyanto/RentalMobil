@extends('layouts.admin')

@push('admin_styles')
<style>
    .grid-dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(79,70,229,0.1), rgba(79,70,229,0));
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .stat-title {
        color: #64748B;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-main);
    }
    
    .text-success { color: #10B981; }
    .text-primary { color: #4F46E5; }
    
    .panel-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    .panel {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .panel-header {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: var(--secondary);
        border-bottom: 2px solid #F1F5F9;
        padding-bottom: 0.8rem;
    }

    .list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #F1F5F9;
    }

    .list-item:last-child {
        border-bottom: none;
    }

    .car-info strong {
        display: block;
        color: var(--secondary);
        font-size: 1.1rem;
    }

    .car-info span {
        color: #64748B;
        font-size: 0.85rem;
    }

    .badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-active { background: #D1FAE5; color: #065F46; }
    .badge-upcoming { background: #DBEAFE; color: #1E40AF; }
    
    .table-container { width: 100%; border-collapse: collapse; }
    .table-container th { text-align: left; padding: 1rem; background: #F8FAFC; color: #64748B; font-size: 0.85rem; text-transform: uppercase; }
    .table-container td { padding: 1rem; border-bottom: 1px solid #E2E8F0; }

</style>
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
