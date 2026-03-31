@extends('layouts.admin')

@section('content')
<h1 class="page-title">Manajemen Pemesanan (Bookings)</h1>

<div class="box" style="padding: 0; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead style="background: #F8FAFC; border-bottom: 2px solid #E2E8F0;">
            <tr>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Pelanggan</th>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Mobil</th>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Durasi Sewa</th>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Total Harga</th>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Status</th>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.3s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                <td style="padding: 1.2rem;">
                    <div style="font-weight: 600; color: var(--text-main);">{{ $booking->user->name }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $booking->user->email }}</div>
                </td>
                <td style="padding: 1.2rem;">
                    <div style="font-weight: 500;">{{ $booking->car->brand }} {{ $booking->car->name }}</div>
                    <div style="font-size: 0.85rem; font-family: monospace;">{{ $booking->car->license_plate }}</div>
                </td>
                <td style="padding: 1.2rem;">
                    <div style="font-size: 0.9rem;">
                        {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} - <br>
                        {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                    </div>
                </td>
                <td style="padding: 1.2rem;">
                    <span style="font-weight: 700; color: var(--accent);">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </td>
                <td style="padding: 1.2rem;">
                    @php
                        $statusColors = [
                            'pending' => ['bg' => '#FEF3C7', 'text' => '#92400E'],
                            'approved' => ['bg' => '#DBEAFE', 'text' => '#1E40AF'],
                            'completed' => ['bg' => '#D1FAE5', 'text' => '#065F46'],
                            'cancelled' => ['bg' => '#FEE2E2', 'text' => '#991B1B'],
                        ];
                        $colors = $statusColors[$booking->status] ?? ['bg' => '#F3F4F6', 'text' => '#374151'];
                    @endphp
                    <span style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                        {{ $booking->status }}
                    </span>
                </td>
                <td style="padding: 1.2rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" style="display: flex; gap: 0.2rem;">
                            @csrf
                            @method('PUT')
                            <select name="status" style="padding: 0.3rem; border-radius: 4px; border: 1px solid #CBD5E1; font-size: 0.8rem;" onchange="this.form.submit()">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                        <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus data pesanan ini?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #EF4444; font-size: 0.8rem; font-weight: 600; cursor: pointer; padding: 0; text-decoration: underline;">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 3rem; color: #64748B;">
                    📅 Belum ada data pemesanan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
