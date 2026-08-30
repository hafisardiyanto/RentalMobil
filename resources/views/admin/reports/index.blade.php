@extends('layouts.admin')

@section('content')
    <div class="box" id="print-area">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;"
            class="no-print">
            <h2>Laporan Keuangan & Operasional</h2>

            <form action="{{ route('admin.reports.index') }}" method="GET"
                style="display: flex; gap: 1rem; align-items: flex-end; background: #F8FAFC; padding: 1rem; border-radius: 8px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Mulai
                        Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        style="padding: 0.5rem; border-radius: 4px; border: 1px solid #CBD5E1;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Sampai
                        Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        style="padding: 0.5rem; border-radius: 4px; border: 1px solid #CBD5E1;">
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">Filter</button>
                @if(request('start_date'))
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">Reset</a>
                @endif
                <button type="button" class="btn btn-outline" style="padding: 0.5rem 1rem;" onclick="window.print()">🖨️
                    Cetak PDF</button>
            </form>
        </div>

        <!-- Ringkasan Finansial -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
            <div style="background: #F1F5F9; border-left: 4px solid #3B82F6; padding: 1.5rem; border-radius: 6px;">
                <p style="margin: 0; color: #64748B; font-weight: bold; font-size: 0.9rem;">Pendapatan Rental (Pokok)</p>
                <h3 style="margin: 0.5rem 0 0 0; color: #1E293B;">Rp {{ number_format($totalSewaPokok, 0, ',', '.') }}</h3>
            </div>
            <div style="background: #FFFBEB; border-left: 4px solid #F59E0B; padding: 1.5rem; border-radius: 6px;">
                <p style="margin: 0; color: #64748B; font-weight: bold; font-size: 0.9rem;">Pemasukan (Denda Telat)</p>
                <h3 style="margin: 0.5rem 0 0 0; color: #92400E;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
            </div>
            <div style="background: #FEF2F2; border-left: 4px solid #EF4444; padding: 1.5rem; border-radius: 6px;">
                <p style="margin: 0; color: #64748B; font-weight: bold; font-size: 0.9rem;">Pemasukan (Ganti Kerusakan)</p>
                <h3 style="margin: 0.5rem 0 0 0; color: #991B1B;">Rp {{ number_format($totalKerusakan, 0, ',', '.') }}</h3>
            </div>
            <div
                style="background: #ECFDF5; border-left: 4px solid #10B981; padding: 1.5rem; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <p style="margin: 0; color: #065F46; font-weight: bold; font-size: 0.9rem;">Total Pendapatan Keseluruhan</p>
                <h2 style="margin: 0.5rem 0 0 0; color: #047857;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
            </div>
        </div>

        <!-- Tabel Rincian Data -->
        <h3 style="margin-bottom: 1rem; border-bottom: 2px solid #E2E8F0; padding-bottom: 0.5rem;">Rincian Transaksi Selesai
        </h3>
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse; min-width: 900px;">
                <thead>
                    <tr style="background: #F8FAFC;">
                        <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: left;">Tanggal Kembali</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: left;">No Booking</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: left;">Mobil</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: right;">Sewa Pokok</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: right;">Denda</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: right;">Kerusakan</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #E2E8F0; text-align: right;">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td style="padding: 1rem; border-bottom: 1px solid #F1F5F9;">
                                {{ $b->waktu_pengembalian ? date('d M Y, H:i', strtotime($b->waktu_pengembalian)) : '-' }}
                            </td>
                            <td style="padding: 1rem; border-bottom: 1px solid #F1F5F9;">
                                <span style="font-weight: bold;">{{ $b->nomor_booking }}</span><br>
                                <small style="color: #64748B;">{{ $b->user->name }}</small>
                            </td>
                            <td style="padding: 1rem; border-bottom: 1px solid #F1F5F9;">{{ $b->car->brand }}
                                {{ $b->car->name }}</td>

                            <td style="padding: 1rem; border-bottom: 1px solid #F1F5F9; text-align: right; color: #3B82F6;">
                                Rp {{ number_format($b->subtotal, 0, ',', '.') }}
                            </td>
                            <td style="padding: 1rem; border-bottom: 1px solid #F1F5F9; text-align: right; color: #D97706;">
                                {{ $b->denda_terlambat > 0 ? 'Rp ' . number_format($b->denda_terlambat, 0, ',', '.') : '-' }}
                            </td>
                            <td style="padding: 1rem; border-bottom: 1px solid #F1F5F9; text-align: right; color: #E11D48;">
                                {{ $b->biaya_kerusakan > 0 ? 'Rp ' . number_format($b->biaya_kerusakan, 0, ',', '.') : '-' }}
                            </td>
                            <td
                                style="padding: 1rem; border-bottom: 1px solid #F1F5F9; text-align: right; font-weight: bold; color: var(--primary);">
                                Rp {{ number_format($b->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem; color: #64748B;">
                                📊 Tidak ada transaksi selesai yang ditemukan dalam rentang tanggal tersebut.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .no-print,
            .sidebar,
            .top-navbar {
                display: none !important;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                border: none !important;
            }

            .box {
                box-shadow: none !important;
                margin: 0;
                padding: 0;
                border: none;
            }
        }
    </style>
@endsection