@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer-booking.css') }}">
@endpush

@section('content')
    <div class="history-container">
        <div class="table-card">
            <h1 class="cs-title">Riwayat Penyewaan Saya</h1>
            <p class="cs-text-muted">Lihat detail mobil yang pernah/sedang Anda sewa di bawah ini.</p>

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
                        <tr class="cs-tr-hover">
                            <td>
                                <div class="cs-fw-700">{{ $booking->car->brand }} {{ $booking->car->name }}</div>
                                <div class="cs-sub-text">
                                    {{ $booking->nomor_booking ?: 'Plat: ' . $booking->car->license_plate }}
                                </div>
                            </td>
                            <td>
                                <div class="cs-date-text">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="cs-accent">Rp
                                    {{ number_format($booking->total, 0, ',', '.') }}
                                </div>
                                @if($booking->totalPaid() > 0 && $booking->remainingBalance() > 0)
                                    <div style="font-size: 0.85rem; color: #ef4444; font-weight: 700; margin-top: 4px;">
                                        Sisa: Rp {{ number_format($booking->remainingBalance(), 0, ',', '.') }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: #10b981; margin-top: 2px;">
                                        Terbayar: Rp {{ number_format($booking->totalPaid(), 0, ',', '.') }}
                                    </div>
                                @endif
                                <div class="cs-date-text">
                                    @if($booking->status_pembayaran !== 'Lunas' && !in_array($booking->status_booking, ['Ditolak', 'Dibatalkan']))
                                        <a href="{{ route('bookings.payment', $booking->id) }}" class="cs-btn-pay">💸 Bayar /
                                            Konfirmasi</a>

                                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST"
                                            class="cs-form-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                                            @csrf
                                            <button type="submit" class="cs-btn-cancel">Batalkan</button>
                                        </form>
                                    @else
                                        <span class="cs-text-bold-muted">{{ $booking->status_pembayaran }}</span>
                                        @if($booking->status_booking === 'Selesai')
                                            <a href="{{ route('bookings.invoice', $booking->id) }}" class="cs-btn-invoice"
                                                target="_blank">📄 Cetak Invoice</a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match ($booking->status_booking) {
                                        'Selesai' => 'bg-available',
                                        'Ditolak', 'Dibatalkan' => 'bg-inactive',
                                        'Menunggu Konfirmasi', 'Menunggu Pembayaran', 'Menunggu Pengembalian' => 'bg-pending',
                                        default => 'bg-rented'
                                    };
                                @endphp
                                <span class="status-badge-cs {{ $badgeClass }}">
                                    {{ $booking->status_booking }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="cs-empty">
                                🚗 Belum ada data penyewaan. Cari mobil unggulan di Beranda!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection