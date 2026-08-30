@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/reports.css') }}">
@endpush

@section('content')
    <div class="box" id="print-area">
        <div class="report-header no-print">
            <h2>Laporan Keuangan & Operasional</h2>

            <form action="{{ route('admin.reports.index') }}" method="GET" class="filter-form">
                <div>
                    <label class="filter-label">Mulai Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="filter-input">
                </div>
                <div>
                    <label class="filter-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="filter-input">
                </div>

                <button type="submit" class="btn btn-primary btn-filter">Filter</button>
                @if(request('start_date'))
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline btn-reset">Reset</a>
                @endif
                <button type="button" class="btn btn-outline btn-print" onclick="window.print()">🖨️ Cetak PDF</button>
            </form>
        </div>

        <!-- Ringkasan Finansial -->
        <div class="summary-grid">
            <div class="summary-card card-primary">
                <p class="summary-title title-primary">Pendapatan Rental (Pokok)</p>
                <h3 class="summary-value val-primary">Rp {{ number_format($totalSewaPokok, 0, ',', '.') }}</h3>
            </div>
            <div class="summary-card card-warning">
                <p class="summary-title title-warning">Pemasukan (Denda Telat)</p>
                <h3 class="summary-value val-warning">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
            </div>
            <div class="summary-card card-danger">
                <p class="summary-title title-danger">Pemasukan (Ganti Kerusakan)</p>
                <h3 class="summary-value val-danger">Rp {{ number_format($totalKerusakan, 0, ',', '.') }}</h3>
            </div>
            <div class="summary-card card-success">
                <p class="summary-title title-success">Total Pendapatan Keseluruhan</p>
                <h2 class="summary-value val-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
            </div>
        </div>

        <!-- Tabel Rincian Data -->
        <h3 class="details-title">Rincian Transaksi Selesai</h3>
        <div class="details-table-wrapper">
            <table class="table details-table">
                <thead>
                    <tr>
                        <th>Tanggal Kembali</th>
                        <th>No Booking</th>
                        <th>Mobil</th>
                        <th class="th-right">Sewa Pokok</th>
                        <th class="th-right">Denda</th>
                        <th class="th-right">Kerusakan</th>
                        <th class="th-right">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td>
                                {{ $b->waktu_pengembalian ? date('d M Y, H:i', strtotime($b->waktu_pengembalian)) : '-' }}
                            </td>
                            <td>
                                <span class="book-no">{{ $b->nomor_booking }}</span><br>
                                <span class="book-user">{{ $b->user->name }}</span>
                            </td>
                            <td>{{ $b->car->brand }} {{ $b->car->name }}</td>

                            <td class="td-right col-primary">
                                Rp {{ number_format($b->subtotal, 0, ',', '.') }}
                            </td>
                            <td class="td-right col-warning">
                                {{ $b->denda_terlambat > 0 ? 'Rp ' . number_format($b->denda_terlambat, 0, ',', '.') : '-' }}
                            </td>
                            <td class="td-right col-danger">
                                {{ $b->biaya_kerusakan > 0 ? 'Rp ' . number_format($b->biaya_kerusakan, 0, ',', '.') : '-' }}
                            </td>
                            <td class="td-right col-total">
                                Rp {{ number_format($b->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-row">
                                📊 Tidak ada transaksi selesai yang ditemukan dalam rentang tanggal tersebut.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection