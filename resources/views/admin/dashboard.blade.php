@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')

    <h1 class="page-title">Dashboard Analitik</h1>

    <div class="dashboard-stats">
        @php
            $carsInMaintenance = \App\Models\Car::where('status_mobil', 'Maintenance')->get();
        @endphp

        @if($carsInMaintenance->count() > 0)
            <div class="alert alert-danger"
                style="grid-column: 1 / -1; background:#fef2f2; border: 1px solid #fecaca; color: #991b1b; padding:1.5rem; border-radius:12px; margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span style="font-size: 2rem;">⚠️</span>
                    <div>
                        <h4 style="margin:0 0 0.5rem 0; font-size:1.1rem; font-weight:700;">Perhatian: Ada Mobil Sedang Diservis
                            (Nonaktif)</h4>
                        <span style="font-size: 0.95rem;">Terdapat <b>{{ $carsInMaintenance->count() }} unit</b> mobil yang
                            tidak bisa disewa oleh pelanggan dan sedang dalam perbaikan:
                            {{ $carsInMaintenance->pluck('name')->join(', ') }}.
                        </span>
                        <br><a href="{{ route('admin.maintenances.index') }}"
                            style="color: #991b1b; font-weight: 600; text-decoration: underline; margin-top: 5px; display: inline-block;">Lihat
                            Jadwal Servis &raquo;</a>
                    </div>
                </div>
            </div>
        @endif

        <div class="stat-card">
            <div class="stat-title">Total Pendapatan (Revenue)</div>
            <div class="stat-value text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="dashboard-desc">Dari seluruh transaksi sukses.</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Mobil Sedang Jalan</div>
            <div class="stat-value text-primary">{{ $activeBookings->count() }} <span class="fw-600">Unit</span></div>
            <div class="dashboard-desc">Lagi disewa per hari ini.</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Reservasi Mendatang</div>
            <div class="stat-value stat-value-upcoming">{{ $upcomingBookings->count() }}</div>
            <div class="dashboard-desc">Jadwal peminjaman antre.</div>
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
                                <td class="fw-600">{{ $booking->user->name }}</td>
                                <td>{{ $booking->car->brand }} {{ $booking->car->name }}</td>
                                <td class="date-urgent">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</td>
                                <td><span class="badge badge-active">Sedang Jalan</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="dashboard-empty-state">
                    💤 Tidak ada mobil yang sedang berjalan hari ini.
                </div>
            @endif

            <h3 class="panel-header dashboard-panel-title">📅 Booking / Reservasi Mendatang</h3>
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
                                <td class="date-upcoming">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="dashboard-empty-state">
                    🗓️ Belum ada daftar pemesanan di masa depan.
                </div>
            @endif
        </div>

        <!-- Kolom Kanan -->
        <div class="panel">
            <h3 class="panel-header">📈 Mobil Paling Laku (Top 5)</h3>
            <div class="flex-column">
                @forelse($frequentCars as $car)
                    <div class="list-item">
                        <div class="car-info">
                            <strong>{{ $car->name }}</strong>
                            <span>{{ $car->brand }} &bull; Plat: {{ $car->license_plate }}</span>
                        </div>
                        <div class="text-right">
                            <span class="stat-large">{{ $car->bookings_count }}</span>
                            <span class="stat-label-small">x disewa</span>
                        </div>
                    </div>
                @empty
                    <div class="dashboard-empty-state">
                        Belum ada data riwayat persewaan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection