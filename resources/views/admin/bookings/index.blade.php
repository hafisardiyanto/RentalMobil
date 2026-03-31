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
                <th>Total Harga</th>
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
                    <span class="price-text">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </td>
                <td>
                    @php
                        $statusColors = [
                            'pending' => ['bg' => '#FEF3C7', 'text' => '#92400E'],
                            'approved' => ['bg' => '#DBEAFE', 'text' => '#1E40AF'],
                            'completed' => ['bg' => '#D1FAE5', 'text' => '#065F46'],
                            'cancelled' => ['bg' => '#FEE2E2', 'text' => '#991B1B'],
                        ];
                        $colors = $statusColors[$booking->status] ?? ['bg' => '#F3F4F6', 'text' => '#374151'];
                    @endphp
                    <span class="status-badge" style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                        {{ $booking->status }}
                    </span>
                </td>
                <td>
                    <div class="action-container">
                        <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" class="status-form">
                            @csrf
                            @method('PUT')
                            <select name="status" class="status-select" onchange="this.form.submit()">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                        <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus data pesanan ini?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
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
