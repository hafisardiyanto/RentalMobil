@extends('layouts.app')

@section('content')
    <style>
        .history-container {
            padding: 5rem 5%;
            background: var(--background);
            min-height: calc(100vh - 80px);
        }

        .table-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            max-width: 1000px;
            margin: 0 auto;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }

        .history-table th {
            text-align: left;
            padding: 1.2rem;
            border-bottom: 2px solid #F1F5F9;
            font-weight: 700;
            color: var(--secondary);
        }

        .history-table td {
            padding: 1.2rem;
            border-bottom: 1px solid #F1F5F9;
        }

        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-pending {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-approved {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .status-completed {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-cancelled {
            background: #FEE2E2;
            color: #991B1B;
        }
    </style>

    <div class="history-container">
        <div class="table-card">
            <h1 style="margin-bottom: 1rem;">Riwayat Penyewaan Saya</h1>
            <p style="color: var(--text-muted);">Lihat detail mobil yang pernah/sedang Anda sewa di bawah ini.</p>

            <table class="history-table">
                <thead>
                    <tr>
                        <th>Mobil & No Pesanan</th>
                        <th>Durasi Sewa</th>
                        <th>Tagihan & Pembayaran</th>
                        <th>Status Booking</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'"
                            style="transition: background 0.3s ease;">
                            <td>
                                <div style="font-weight: 700;">{{ $booking->car->brand }} {{ $booking->car->name }}</div>
                                <div style="font-size: 0.85rem; color: #94A3B8; margin-top: 5px;">
                                    {{ $booking->nomor_booking ?: 'Plat: ' . $booking->car->license_plate }}</div>
                            </td>
                            <td>
                                <div style="font-size: 0.95rem;">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--accent);">Rp
                                    {{ number_format($booking->total, 0, ',', '.') }}
                                </div>
                                <div style="margin-top: 5px; font-size: 0.85rem;">
                                    @if(in_array($booking->status_pembayaran, ['Belum Bayar', 'Menunggu Verifikasi']) && !in_array($booking->status_booking, ['Ditolak', 'Dibatalkan']))
                                        <a href="{{ route('bookings.payment', $booking->id) }}" style="color: white; background: var(--primary); padding: 4px 10px; border-radius: 6px; text-decoration: none; font-weight: bold; display: inline-block; margin-bottom: 5px;">💸 Bayar / Konfirmasi</a>
                                        
                                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; color: #EF4444; font-weight: bold; font-size: 0.85rem; cursor: pointer; text-decoration: underline;">Batalkan</button>
                                        </form>
                                    @else
                                        <span style="color: #64748B; font-weight: bold;">{{ $booking->status_pembayaran }}</span>
                                        @if($booking->status_booking === 'Selesai')
                                            <a href="{{ route('bookings.invoice', $booking->id) }}" style="color: #047857; font-size: 0.85rem; font-weight: bold; text-decoration: underline; display: block; margin-top: 5px;" target="_blank">📄 Cetak Invoice</a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $colors = match ($booking->status_booking) {
                                        'Selesai' => ['bg' => '#D1FAE5', 'text' => '#065F46'],
                                        'Ditolak', 'Dibatalkan' => ['bg' => '#FEE2E2', 'text' => '#991B1B'],
                                        'Menunggu Konfirmasi', 'Menunggu Pembayaran', 'Menunggu Pengembalian' => ['bg' => '#FEF3C7', 'text' => '#92400E'],
                                        default => ['bg' => '#DBEAFE', 'text' => '#1E40AF']
                                    };
                                @endphp
                                <span class="status-badge"
                                    style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                    {{ $booking->status_booking }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 4rem; color: #94A3B8;">
                                🚗 Belum ada data penyewaan. Cari mobil unggulan di Beranda!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection