@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/bookings.css') }}">
@endpush

@section('content')
    <h1 class="page-title">Manajemen Pemesanan (Bookings)</h1>

    <div class="box table-box">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Mobil</th>
                    <th>Durasi Sewa</th>
                    <th>Total Harga & Pembayaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <div class="customer-name">{{ $booking->user->name }}</div>
                            <div class="customer-email">{{ $booking->user->email }}</div>
                            <div class="customer-phone">📞 {{ $booking->user->phone ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="car-info">{{ $booking->car->brand }} {{ $booking->car->name }}</div>
                            <div class="car-plate">{{ $booking->car->license_plate }}</div>
                        </td>
                        <td>
                            <div class="duration-text">
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} - <br>
                                {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                            </div>
                        </td>
                        <td>
                            <span class="price-text price-block">Rp {{ number_format($booking->total, 0, ',', '.') }}</span>
                            @if($booking->payment_proof)
                                <a href="{{ $booking->payment_proof }}" target="_blank" class="link-detail">🔍 Lihat Bukti TF</a>
                            @else
                                <span class="unpaid-text">Belum bayar</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClasses = [
                                    'Menunggu Konfirmasi' => 'bg-pending',
                                    'Menunggu Pembayaran' => 'bg-pending',
                                    'Pembayaran Diverifikasi' => 'bg-rented',
                                    'Booking Dikonfirmasi' => 'bg-rented',
                                    'Sedang Disewa' => 'bg-rented',
                                    'Menunggu Pengembalian' => 'bg-pending',
                                    'Selesai' => 'bg-available',
                                    'Dibatalkan' => 'bg-inactive',
                                    'Ditolak' => 'bg-inactive',
                                ];
                                $badgeClass = $statusClasses[$booking->status_booking] ?? 'bg-default';
                            @endphp
                            <span class="status-badge {{ $badgeClass }}">
                                {{ $booking->status_booking }}
                            </span>
                        </td>
                        <td>
                            <div class="action-container">
                                @can('edit_bookings')
                                    <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST"
                                        class="status-form">
                                        @csrf
                                        @method('PUT')
                                        <select name="status_booking" class="status-select" onchange="this.form.submit()">
                                            <option value="Menunggu Konfirmasi" {{ $booking->status_booking == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                            <option value="Menunggu Pembayaran" {{ $booking->status_booking == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                            <option value="Pembayaran Diverifikasi" {{ $booking->status_booking == 'Pembayaran Diverifikasi' ? 'selected' : '' }}>Pembayaran Diverifikasi</option>
                                            <option value="Booking Dikonfirmasi" {{ $booking->status_booking == 'Booking Dikonfirmasi' ? 'selected' : '' }}>Booking Dikonfirmasi</option>
                                            <option value="Sedang Disewa" {{ $booking->status_booking == 'Sedang Disewa' ? 'selected' : '' }}>Sedang Disewa</option>
                                            <option value="Menunggu Pengembalian" {{ $booking->status_booking == 'Menunggu Pengembalian' ? 'selected' : '' }}>Menunggu Pengembalian</option>
                                            <option value="Selesai" {{ $booking->status_booking == 'Selesai' ? 'selected' : '' }}>
                                                Selesai</option>
                                            <option value="Ditolak" {{ $booking->status_booking == 'Ditolak' ? 'selected' : '' }}>
                                                Ditolak</option>
                                            <option value="Dibatalkan" {{ $booking->status_booking == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </form>
                                @endcan
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn-detail">➡️ Detail &
                                    Proses</a>

                                @can('delete_bookings')
                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data pesanan ini?')" class="form-delete-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">Hapus</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            📅 Belum ada data pemesanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection