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
                                            <!-- Review Button Logic -->
                                            @if(!\App\Models\Review::where('booking_id', $booking->id)->exists())
                                                <button
                                                    onclick="document.getElementById('reviewModal-{{ $booking->id }}').style.display='block'"
                                                    class="cs-btn-pay"
                                                    style="background-color:#f59e0b; margin-top:5px; border-color:#f59e0b; cursor:pointer;">⭐
                                                    Beri Ulasan</button>

                                                <!-- The Modal -->
                                                <div id="reviewModal-{{ $booking->id }}" class="modal"
                                                    style="display:none; position:fixed; z-index:100; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.5);">
                                                    <div
                                                        style="background-color:#fff; margin:10% auto; padding:25px; border:1px solid #888; width:90%; max-width:500px; border-radius:12px; position:relative; text-align:left;">
                                                        <span
                                                            onclick="document.getElementById('reviewModal-{{ $booking->id }}').style.display='none'"
                                                            style="color:#64748b; position:absolute; top:15px; right:20px; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
                                                        <h3 style="margin-top:0; color:#1e293b;">🌟 Pengalaman Sewa</h3>
                                                        <p style="color:#64748b; font-size:0.9rem; margin-bottom:20px;">Berikan ulasan Anda
                                                            tentang <b>{{ $booking->car->brand }} {{ $booking->car->name }}</b>.</p>
                                                        <form action="{{ route('reviews.store') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                            <div style="margin-bottom:15px;">
                                                                <label
                                                                    style="display:block; font-weight:bold; margin-bottom:5px; color:#1e293b;">Rating
                                                                    Bintang</label>
                                                                <select name="rating" required
                                                                    style="width:100%; padding:10px; border-radius:8px; border:1px solid #cbd5e1;">
                                                                    <option value="5">⭐⭐⭐⭐⭐ (Sangat Memuaskan)</option>
                                                                    <option value="4">⭐⭐⭐⭐ (Memuaskan)</option>
                                                                    <option value="3">⭐⭐⭐ (Cukup)</option>
                                                                    <option value="2">⭐⭐ (Kurang Baik)</option>
                                                                    <option value="1">⭐ (Sangat Buruk)</option>
                                                                </select>
                                                            </div>
                                                            <div style="margin-bottom:20px;">
                                                                <label
                                                                    style="display:block; font-weight:bold; margin-bottom:5px; color:#1e293b;">Komentar
                                                                    / Ulasan</label>
                                                                <textarea name="comment" required rows="4"
                                                                    placeholder="Ceritakan kondisi mobil, pelayanan CS/driver, dll..."
                                                                    style="width:100%; padding:10px; border-radius:8px; border:1px solid #cbd5e1;"></textarea>
                                                            </div>
                                                            <button type="submit" class="cs-btn-pay"
                                                                style="width:100%; background:#2563eb; border-color:#2563eb;">Kirim
                                                                Ulasan</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="cs-text-muted" style="display:block; margin-top:5px; font-size:0.8rem;">✔️
                                                    Ulasan telah diberikan</span>
                                            @endif
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