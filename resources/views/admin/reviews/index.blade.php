@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title m-0">Manajemen Testimoni Pelanggan</h1>
            <p>Kelola ulasan dari pelanggan untuk ditampilkan di halaman depan.</p>
        </div>
    </div>

    <div class="box">
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Mobil (Booking ID)</th>
                        <th>Rating</th>
                        <th>Ulasan</th>
                        <th>Status Homepage</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->user->name }}</td>
                            <td>
                                @if($review->car)
                                    {{ $review->car->brand }} {{ $review->car->name }}
                                @else
                                    Mobil Terhapus
                                @endif
                                <br><small>#RM-{{ str_pad($review->booking_id, 4, '0', STR_PAD_LEFT) }}</small>
                            </td>
                            <td>{{ str_repeat('⭐', $review->rating) }} ({{ $review->rating }}/5)</td>
                            <td style="max-width: 300px; white-space: normal;">"{{ $review->comment }}"</td>
                            <td>
                                @if($review->is_featured)
                                    <span class="badge" style="background:#10b981;">Ditampilkan</span>
                                @else
                                    <span class="badge" style="background:#94a3b8;">Disembunyikan</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap: 5px;">
                                    <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        @if($review->is_featured)
                                            <button type="submit" class="btn btn-sm btn-outline"
                                                style="border-color:#f59e0b; color:#f59e0b;">Sembunyikan</button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                                        @endif
                                    </form>
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus ulasan ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline"
                                            style="border-color:#ef4444; color:#ef4444;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">Belum ada ulasan dari pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection