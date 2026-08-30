@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/bookings.css') }}">
@endpush

@section('content')
    <div class="box">
        <div class="booking-header">
            <div>
                <h2>Detail Booking: {{ $booking->nomor_booking }}</h2>
                <p class="booking-date">Dipesan pada {{ $booking->created_at->format('d M Y, H:i') }}
                </p>
            </div>
            <div style="text-align: right;">
                <div class="status-row">
                    <span class="status-pill">Status:
                        {{ $booking->status_booking }}</span>
                </div>
                <div>
                    <span class="status-pill">Tagihan:
                        {{ $booking->status_pembayaran }}</span>
                </div>
            </div>
        </div>

        <div class="info-grid">
            <!-- INFO PELANGGAN -->
            <div>
                <h3 class="info-section-title">👤 Info Pelanggan</h3>
                <table class="info-table">
                    <tr>
                        <td class="label-col">Nama</td>
                        <td class="info-val">: {{ $booking->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">No WhatsApp</td>
                        <td class="info-val">: {{ $booking->user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">NIK</td>
                        <td class="info-val">: {{ $booking->user->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Alamat KTP</td>
                        <td class="info-val">: {{ $booking->user->address ?? '-' }}</td>
                    </tr>
                </table>

                @if($booking->user->ktp_photo || $booking->user->sim_photo)
                    <div class="doc-thumbnails">
                        @if($booking->user->ktp_photo)
                            <div><small>KTP:</small><br><img src="{{ $booking->user->ktp_photo }}"
                                    onclick="window.open(this.src)"></div>
                        @endif
                        @if($booking->user->sim_photo)
                            <div><small>SIM:</small><br><img src="{{ $booking->user->sim_photo }}"
                                    onclick="window.open(this.src)"></div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- INFO MOBIL & HARGA -->
            <div>
                <h3 class="info-section-title">🚗 Info Sewa & Keuangan</h3>
                <table class="info-table">
                    <tr>
                        <td class="label-col">Mobil</td>
                        <td class="info-val">: {{ $booking->car->brand }} {{ $booking->car->name }}
                            ({{ $booking->car->license_plate }})</td>
                    </tr>
                    <tr>
                        <td class="label-col">Durasi</td>
                        <td class="info-val">: {{ date('d M Y', strtotime($booking->start_date)) }} s/d
                            {{ date('d M Y', strtotime($booking->end_date)) }} ({{ $booking->durasi }} Hari)
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col">Biaya Sewa Pokok</td>
                        <td class="info-val text-success">: Rp
                            {{ number_format($booking->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col">Uang Jaminan (Deposit)</td>
                        <td class="info-val text-warning">: Rp
                            {{ number_format($booking->deposit, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col">Total Tagihan Akhir</td>
                        <td class="info-val text-danger large-price">: Rp
                            {{ number_format($booking->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>

                @if($booking->payment_proof)
                    <div style="margin-top: 1rem;">
                        <small>Bukti Transfer (Customer):</small><br>
                        <img src="{{ $booking->payment_proof }}" class="proof-img" onclick="window.open(this.src)">
                    </div>
                @endif
            </div>
        </div>

        <!-- ACTION AREA (SMART BUTTONS) -->
        <div class="action-area">
            <h3 class="action-area-title">Tindakan Operasional</h3>

            @if($booking->status_pembayaran === 'Menunggu Verifikasi')
                <div class="action-panel panel-primary">
                    <h4>✓ Verifikasi Pembayaran Customer</h4>
                    <p class="panel-desc">Customer telah mengunggah bukti pembayaran. Harap pastikan mutasi rekening valid sebelum menekan Lunas.</p>
                    @can('edit_bookings')
                        <form action="{{ route('admin.bookings.update-payment-status', $booking->id) }}" method="POST"
                            style="display: flex; gap: 1rem; align-items: center;">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="b-label">Input Nominal Deposit (Jaminan)</label>
                                <input type="number" name="deposit" value="{{ $booking->deposit > 0 ? $booking->deposit : '' }}"
                                    placeholder="Rp 0 (Kosongkan bila tidak ada)"
                                    class="b-input" style="width: 250px;">
                            </div>

                            <input type="hidden" name="status_pembayaran" value="Lunas">
                            <button type="submit" class="btn btn-primary" style="margin-top: 23px;">Lunas & Dikonfirmasi</button>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('reject-form').submit();"
                                style="color: #EF4444; font-weight: bold; margin-top: 23px; margin-left: 1rem;">Tolak & Batalkan</a>
                        </form>
                        <form id="reject-form" action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status_booking" value="Ditolak">
                        </form>
                    @else
                        <p style="color: #EF4444; font-weight:bold;">Hubungi Kasir yang berwenang untuk memverifikasi pesanan ini.</p>
                    @endcan
                </div>

            @elseif($booking->status_booking === 'Booking Dikonfirmasi')
                <div class="action-panel panel-warning">
                    <h4>🚗 Proses Serah Terima (Handover)</h4>
                    <p class="panel-desc">Lengkapi data awal kendaraan sebelum pelanggan membawa mobil keluar garasi.</p>

                    @can('edit_bookings')
                        <form action="{{ route('admin.bookings.process-handover', $booking->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-grid-2">
                                <div>
                                    <label class="b-label">KM Berangkat Odometer</label>
                                    <input type="number" name="km_awal" required class="b-input">
                                </div>
                                <div>
                                    <label class="b-label">Posisi BBM Awal (Misal: 3/4)</label>
                                    <input type="text" name="bbm_awal" required class="b-input">
                                </div>
                            </div>
                            <div class="b-form-group">
                                <label class="b-label">Cacat / Kondisi Awal Fisik Mobil</label>
                                <textarea name="kondisi_awal" required class="b-input" rows="2"></textarea>
                            </div>
                            <div class="b-form-group">
                                <label class="b-label">Foto Aktual Mobil Sebelum Diserahkan</label>
                                <input type="file" name="foto_awal" accept="image/*" required class="b-input">
                            </div>
                            <button type="submit" class="btn-start">Mulai Masa Sewa -> Sedang Disewa</button>
                        </form>
                    @else
                        <p style="color: #EF4444; font-weight:bold;">Hubungi staf operasional untuk melakukan proses Serah Terima.</p>
                    @endcan
                </div>

            @elseif($booking->status_booking === 'Sedang Disewa')
                <div class="action-panel panel-success">
                    <h4>🔙 Proses Pengembalian (Return)</h4>
                    <p class="panel-desc">Lengkapi data akhir kedatangan mobil. Setelah ini, Anda dapat merinci Denda atau Kerusakan jika ada sebelum Finalisasi Invoice.</p>

                    @can('edit_bookings')
                        <form action="{{ route('admin.bookings.process-return', $booking->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-grid-2">
                                <div>
                                    <label class="b-label">KM Akhir Odometer</label>
                                    <input type="number" name="km_akhir" required class="b-input">
                                </div>
                                <div>
                                    <label class="b-label">Posisi BBM Akhir</label>
                                    <input type="text" name="bbm_akhir" required class="b-input">
                                </div>
                            </div>

                            <div class="b-form-group">
                                <label class="b-label">Kondisi Awal Saat Kembali (Umum)</label>
                                <textarea name="kondisi_akhir" required class="b-input" rows="2">Mobil kembali dalam keadaan aman.</textarea>
                            </div>
                            <div class="b-form-group">
                                <label class="b-label">Foto Aktual Mobil Saat Dikembalikan</label>
                                <input type="file" name="foto_akhir" accept="image/*" required class="b-input">
                            </div>
                            <button type="submit" class="btn-return">Mobil Diterima (Lanjut Pemeriksaan)</button>
                        </form>
                    @else
                        <p style="color: #EF4444; font-weight:bold;">Hubungi staf operasional untuk melakukan proses Pengembalian Kendaraan.</p>
                    @endcan
                </div>
            @elseif($booking->status_booking === 'Pemeriksaan')
                <div class="action-panel panel-warning">
                    <h4>🔍 Pemeriksaan Rincian Denda & Kerusakan</h4>
                    <p class="panel-desc">Tambahkan log/rincian satu per satu jika ada kerusakan atau
                        denda terlambat. Transparansi sangat penting.</p>

                    <!-- ADD FINE FORM -->
                    @can('manage_fines')
                        <form action="{{ route('admin.fines.store', $booking->id) }}" method="POST" enctype="multipart/form-data" class="fines-form-box">
                            @csrf
                            <div class="form-grid-3">
                                <div>
                                    <label class="b-label b-label-sm">Jenis Biaya</label>
                                    <select name="type" required class="b-input b-input-sm">
                                        <option value="Kerusakan">Kerusakan</option>
                                        <option value="Denda Terlambat">Denda Terlambat</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="b-label b-label-sm">Bagian Mobil (Opsional)</label>
                                    <input type="text" name="part_name" placeholder="Misal: Bumper Belakang" class="b-input b-input-sm">
                                </div>
                                <div>
                                    <label class="b-label b-label-sm">Tagihan (Rp)</label>
                                    <input type="number" name="amount" required class="b-input b-input-sm">
                                </div>
                            </div>
                            <div class="form-grid-2-1">
                                <div>
                                    <label class="b-label b-label-sm">Deskripsi Detail Kerusakan</label>
                                    <input type="text" name="description" required placeholder="Misal: Lecet cukup dalam akibat goresan" class="b-input b-input-sm">
                                </div>
                                <div>
                                    <label class="b-label b-label-sm">Upload Bukti Foto</label>
                                    <input type="file" name="photo" accept="image/*" class="b-input b-input-sm">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" style="padding: 0.4rem 1rem;">+ Tambah Log Biaya</button>
                        </form>
                    @endcan

                    <!-- FINES TABLE -->
                    @if($booking->fines->count() > 0)
                        <table class="fines-table">
                            <thead>
                                <tr>
                                    <th>Daftar Biaya</th>
                                    <th>Bagian / Deskripsi</th>
                                    <th style="text-align: right;">Nominal (Rp)</th>
                                    <th>Dicatat Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->fines as $fine)
                                    <tr>
                                        <td><b>{{ $fine->type }}</b><br>
                                            @if($fine->photo_path)
                                                <a href="{{ Storage::url($fine->photo_path) }}" target="_blank"
                                                    class="fine-proof-link">Lihat Bukti Foto</a>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $fine->part_name ? $fine->part_name . ' - ' : '' }}{{ $fine->description }}</td>
                                        <td class="fine-amount">
                                            {{ number_format($fine->amount, 0, ',', '.') }}</td>
                                        <td>{{ $fine->user->name ?? 'Admin' }}<br><small
                                                class="fine-date">{{ $fine->created_at->format('d M H:i') }}</small></td>
                                        <td>
                                            @can('manage_fines')
                                                <form action="{{ route('admin.fines.destroy', $fine->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus item ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        style="background:none; border:none; color:#EF4444; font-weight:bold; cursor:pointer;"
                                                        title="Hapus">X</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <!-- AUDIT LOGS -->
                    @if($booking->fineAuditLogs->count() > 0)
                        <div class="audit-log-box">
                            <h5 class="audit-log-title">📜 Riwayat Audit Trail Kerusakan & Denda</h5>
                            <ul class="audit-log-list">
                                @foreach($booking->fineAuditLogs()->orderBy('created_at', 'desc')->get() as $log)
                                    <li>
                                        <b>{{ $log->user->name ?? 'User' }}</b> ({{ $log->created_at->format('d M Y, H:i:s') }}):
                                        @if($log->action == 'Added') <span style="color:#10B981;">[Menambah]</span>
                                        @elseif($log->action == 'Deleted') <span style="color:#EF4444;">[Menghapus]</span>
                                        @else <span style="color:#F59E0B;">[Ubah]</span> @endif
                                        {{ $log->details }}
                                        (Rp {{ number_format($log->old_amount, 0, ',', '.') }} &rarr; Rp
                                        {{ number_format($log->new_amount, 0, ',', '.') }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @can('edit_bookings')
                        <form action="{{ route('admin.bookings.finalize', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-finalize">🏁 SIMPAN SEMUA & FINALISASI INVOICE</button>
                        </form>
                    @else
                        <p class="pending-cashier-msg">Menunggu kasir utama untuk Finalisasi Invoice & Denda.</p>
                    @endcan
                </div>
            @elseif($booking->status_booking === 'Menunggu Pelunasan')
                <div class="action-panel panel-danger">
                    <h4>⚠️ Menunggu Pelunasan Tagihan Susulan</h4>
                    <p class="panel-desc">Pelanggan memiliki Tagihan Susulan sebesar <b>Rp
                            {{ number_format($booking->tagihan_susulan, 0, ',', '.') }}</b> yang melebihi batas Deposit.
                        Transaksi tidak dapat diselesaikan ke tahap Selesai sebelum tunggakan dilunasi.</p>
                    @can('edit_bookings')
                        <form action="{{ route('admin.bookings.update-payment-status', $booking->id) }}" method="POST"
                            style="margin-top: 1rem;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status_pembayaran" value="Lunas">
                            <input type="hidden" name="deposit" value="{{ $booking->deposit }}">
                            <button type="submit" class="btn-return">Konfirmasi Pelunasan & Tutup Booking Transaksi (Lunas)</button>
                        </form>
                    @endcan
                </div>
            @elseif($booking->status_booking === 'Selesai')
                <div class="completed-box">
                    <h3>✅ Transaksi Selesai</h3>
                    <p>Mobil telah dikembalikan dan Invoice telah resmi diterbitkan.</p>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary" style="margin-top: 1rem;">Kembali ke
                        Riwayat</a>
                    <a href="{{ route('bookings.invoice', $booking->id) }}" class="btn-print" target="_blank">Cetak Invoice Final</a>
                </div>
            @else
                <div style="text-align: center; padding: 2rem;">
                    <p style="color: #64748b;">Menunggu aksi dari Pelanggan. (Status: {{ $booking->status_booking }})</p>
                </div>
            @endif
        </div>

    </div>
@endsection