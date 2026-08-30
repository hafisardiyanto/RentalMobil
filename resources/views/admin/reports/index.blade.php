@extends('layouts.admin')

@section('content')
    <div class="box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>Laporan Pendapatan Rental</h2>

            <form action="{{ route('admin.reports.index') }}" method="GET"
                style="display: flex; gap: 1rem; align-items: center;">
                <label for="bulan" style="font-weight: bold;">Filter Bulan:</label>
                <input type="month" name="bulan" id="bulan" value="{{ request('bulan') }}"
                    style="padding: 0.5rem; border-radius: 4px; border: 1px solid #CBD5E1;">
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">Tampilkan</button>
                @if(request('bulan'))
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">Reset</a>
                @endif
            </form>
        </div>

        <div
            style="background: #ECFDF5; border: 1px solid #10B981; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
            <h3 style="margin: 0; color: #065F46;">Total Pendapatan
                {{ request('bulan') ? 'Bulan ' . date('F Y', strtotime(request('bulan'))) : 'Keseluruhan' }}</h3>
            <p style="font-size: 2rem; font-weight: bold; margin: 0.5rem 0 0 0; color: #047857;">Rp
                {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>

        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: left;">Tanggal Kembali</th>
                    <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: left;">No Booking</th>
                    <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: left;">Customer</th>
                    <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: left;">Mobil</th>
                    <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: right;">Total Tagihan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #F1F5F9;">
                            {{ $b->waktu_pengembalian ? date('d M Y, H:i', strtotime($b->waktu_pengembalian)) : '-' }}
                        </td>
                        <td style="padding: 1rem; border-bottom: 1px solid #F1F5F9;">{{ $b->nomor_booking }}</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #F1F5F9;">{{ $b->user->name }}</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #F1F5F9;">{{ $b->car->brand }} {{ $b->car->name }}
                        </td>
                        <td
                            style="padding: 1rem; border-bottom: 1px solid #F1F5F9; text-align: right; font-weight: bold; color: var(--primary);">
                            Rp {{ number_format($b->total, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: #64748B;">Tidak ada transaksi yang
                            selesai pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection