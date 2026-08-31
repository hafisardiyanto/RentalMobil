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
            <div class="status-box">
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
                            <div><small>KTP:</small><br><img src="{{ $booking->user->ktp_photo }}" onclick="window.open(this.src)">
                            </div>
                        @endif
                        @if($booking->user->sim_photo)
                            <div><small>SIM:</small><br><img src="{{ $booking->user->sim_photo }}" onclick="window.open(this.src)">
                            </div>
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

                @if($booking->payments && $booking->payments->count() > 0)
                    <div class="proof-box" style="margin-top: 1rem;">
                        <h4
                            style="margin-top:0; font-size:1rem; color:#1e293b; border-bottom:1px solid #e2e8f0; padding-bottom:5px;">
                            Riwayat Pembayaran ({{ $booking->payments->count() }})</h4>
                        <table style="width: 100%; text-align: left; border-collapse: collapse; font-size:0.9rem;">
                            @foreach($booking->payments as $payment)
                                <tr style="border-bottom:1px solid #e2e8f0;">
                                    <td style="padding: 8px 0;">
                                        <b>{{ $payment->type }}</b><br>
                                        <span style="color:#64748b;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td style="padding: 8px 0; text-align:right;">
                                        @if($payment->status === 'Menunggu Verifikasi')
                                            <span class="badge"
                                                style="background: #fef08a; color: #854d0e; padding:3px 6px; border-radius:4px; font-size:0.75rem;">Menunggu</span>
                                        @elseif($payment->status === 'Diterima')
                                            <span class="badge"
                                                style="background: #bbf7d0; color: #166534; padding:3px 6px; border-radius:4px; font-size:0.75rem;">Diterima</span>
                                        @else
                                            <span class="badge"
                                                style="background: #fecaca; color: #991b1b; padding:3px 6px; border-radius:4px; font-size:0.75rem;">Ditolak</span>
                                        @endif
                                        <br>
                                        <a href="{{ $payment->payment_proof }}" target="_blank"
                                            style="color: #3b82f6; font-size: 0.8rem;">Cek Bukti</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- ACTION AREA (SMART BUTTONS) -->
        <div class="action-area">
            <h3 class="action-area-title">Tindakan Operasional</h3>

            @if($booking->payments && $booking->payments->where('status', 'Menunggu Verifikasi')->count() > 0)
                <div class="action-panel panel-primary">
                    <h4>✓ Verifikasi Pembayaran Baru</h4>
                    <p class="panel-desc">Terdapat mutasi masuk yang dikirim oleh customer dan menunggu Validasi
                        (DP/Lunas/Deposit).</p>
                    @can('edit_bookings')
                        @foreach($booking->payments->where('status', 'Menunggu Verifikasi') as $pendingPayment)
                            <div
                                style="background: #fff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1rem; margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <strong>{{ $pendingPayment->type }}</strong> - Rp
                                    {{ number_format($pendingPayment->amount, 0, ',', '.') }}<br>
                                    <a href="{{ $pendingPayment->payment_proof }}" target="_blank"
                                        style="font-size:0.85rem; color:#0284c7; text-decoration:underline;">Lihat Gambar Bukti Transfer
                                        &raquo;</a>
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <form action="{{ route('admin.payments.verify', $pendingPayment->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Diterima">
                                        <button class="btn btn-sm btn-success"
                                            style="background:#10b981; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer; font-weight:bold;">Terima</button>
                                    </form>
                                    <form action="{{ route('admin.payments.verify', $pendingPayment->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Ditolak">
                                        <button class="btn btn-sm btn-danger"
                                            style="background:#ef4444; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer; font-weight:bold;">Tolak</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-danger-bold">Hubungi Kasir yang berwenang untuk memverifikasi pesanan ini.</p>
                    @endcan
                </div>

            @elseif($booking->status_booking === 'Booking Dikonfirmasi')
                <div class="action-panel panel-warning">
                    <h4>🚗 Proses Serah Terima (Handover)</h4>
                    <p class="panel-desc">Lengkapi data awal kendaraan sebelum pelanggan membawa mobil keluar garasi.</p>

                    @can('edit_bookings')
                        <form action="{{ route('admin.bookings.process-handover', $booking->id) }}" method="POST"
                            enctype="multipart/form-data">
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
                        <p class="text-danger-bold">Hubungi staf operasional untuk melakukan proses Serah Terima.</p>
                    @endcan
                </div>

            @elseif($booking->status_booking === 'Sedang Disewa')
                <div class="action-panel panel-success">
                    <h4>🔙 Proses Pengembalian (Return)</h4>
                    <p class="panel-desc">Lengkapi data akhir kedatangan mobil. Setelah ini, Anda dapat merinci Denda atau
                        Kerusakan jika ada sebelum Finalisasi Invoice.</p>

                    @can('edit_bookings')
                        <form action="{{ route('admin.bookings.process-return', $booking->id) }}" method="POST"
                            enctype="multipart/form-data">
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
                                <textarea name="kondisi_akhir" required class="b-input"
                                    rows="2">Mobil kembali dalam keadaan aman.</textarea>
                            </div>
                            <div class="b-form-group">
                                <label class="b-label">Foto Aktual Mobil Saat Dikembalikan</label>
                                <input type="file" name="foto_akhir" accept="image/*" required class="b-input">
                            </div>
                            <button type="submit" class="btn-return">Mobil Diterima (Lanjut Pemeriksaan)</button>
                        </form>
                    @else
                        <p class="text-danger-bold">Hubungi staf operasional untuk melakukan proses Pengembalian Kendaraan.</p>
                    @endcan
                </div>
            @elseif($booking->status_booking === 'Pemeriksaan')
                <div class="action-panel panel-warning">
                    <h4>🔍 Pemeriksaan Rincian Denda & Kerusakan</h4>
                    <p class="panel-desc">Tambahkan log/rincian satu per satu jika ada kerusakan atau denda terlambat.
                        Transparansi sangat penting.</p>

                    <!-- ADD FINE FORM -->
                    @can('manage_fines')
                        <form action="{{ route('admin.fines.store', $booking->id) }}" method="POST" enctype="multipart/form-data"
                            class="fines-form-box">
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
                                    <input type="text" name="part_name" placeholder="Misal: Bumper Belakang"
                                        class="b-input b-input-sm">
                                </div>
                                <div>
                                    <label class="b-label b-label-sm">Tagihan (Rp)</label>
                                    <input type="number" name="amount" required class="b-input b-input-sm">
                                </div>
                            </div>
                            <div class="form-grid-2-1">
                                <div>
                                    <label class="b-label b-label-sm">Deskripsi Detail Kerusakan</label>
                                    <input type="text" name="description" required
                                        placeholder="Misal: Lecet cukup dalam akibat goresan" class="b-input b-input-sm">
                                </div>
                                <div>
                                    <label class="b-label b-label-sm">Upload Bukti Foto</label>
                                    <input type="file" name="photo" accept="image/*" class="b-input b-input-sm">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm-primary">+ Tambah Log Biaya</button>
                        </form>
                    @endcan

                    <!-- FINES TABLE -->
                    @if($booking->fines->count() > 0)
                        <table class="fines-table">
                            <thead>
                                <tr>
                                    <th>Daftar Biaya</th>
                                    <th>Bagian / Deskripsi</th>
                                    <th class="th-right">Nominal (Rp)</th>
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
                                            {{ $fine->part_name ? $fine->part_name . ' - ' : '' }}{{ $fine->description }}
                                        </td>
                                        <td class="fine-amount">
                                            {{ number_format($fine->amount, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $fine->user->name ?? 'Admin' }}<br><small
                                                class="fine-date">{{ $fine->created_at->format('d M H:i') }}</small></td>
                                        <td>
                                            @can('manage_fines')
                                                <form action="{{ route('admin.fines.destroy', $fine->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus item ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-delete-icon" title="Hapus">X</button>
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
                                        @if($log->action == 'Added') <span class="log-added">[Menambah]</span>
                                        @elseif($log->action == 'Deleted') <span class="log-deleted">[Menghapus]</span>
                                        @else <span class="log-updated">[Ubah]</span> @endif
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
                            class="btn-mt">
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
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary btn-mt">Kembali ke Riwayat</a>
                    <a href="{{ route('bookings.invoice', $booking->id) }}" class="btn-print" target="_blank">Cetak Invoice
                        Final</a>
                </div>
            @else
                <div class="empty-msg">
                    <p>Menunggu aksi dari Pelanggan. (Status: {{ $booking->status_booking }})</p>
                </div>
            @endif
        </div>

    </div>
@endsection