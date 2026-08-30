@extends('layouts.admin')

@section('content')
    <div class="box">
        <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 2px solid #F1F5F9; padding-bottom: 1rem;">
            <div>
                <h2 style="margin: 0; color: #1e293b;">Detail Booking: {{ $booking->nomor_booking }}</h2>
                <p style="margin: 5px 0 0 0; color: #64748b;">Dipesan pada {{ $booking->created_at->format('d M Y, H:i') }}
                </p>
            </div>
            <div style="text-align: right;">
                <div style="margin-bottom: 5px;">
                    <span
                        style="background: #E2E8F0; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-size: 0.85rem;">Status:
                        {{ $booking->status_booking }}</span>
                </div>
                <div>
                    <span
                        style="background: #E2E8F0; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-size: 0.85rem;">Tagihan:
                        {{ $booking->status_pembayaran }}</span>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem;">
            <!-- INFO PELANGGAN -->
            <div>
                <h3 style="color: var(--primary); margin-bottom: 1rem;">👤 Info Pelanggan</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 150px;">Nama</td>
                        <td style="font-weight: bold;">: {{ $booking->user->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;">No WhatsApp</td>
                        <td style="font-weight: bold;">: {{ $booking->user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;">NIK</td>
                        <td style="font-weight: bold;">: {{ $booking->user->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Alamat KTP</td>
                        <td style="font-weight: bold;">: {{ $booking->user->address ?? '-' }}</td>
                    </tr>
                </table>

                @if($booking->user->ktp_photo || $booking->user->sim_photo)
                    <div style="margin-top: 1rem; display: flex; gap: 1rem;">
                        @if($booking->user->ktp_photo)
                            <div><small>KTP:</small><br><img src="{{ $booking->user->ktp_photo }}"
                                    style="height: 60px; border-radius: 4px; border: 1px solid #ccc; cursor: pointer;"
                                    onclick="window.open(this.src)"></div>
                        @endif
                        @if($booking->user->sim_photo)
                            <div><small>SIM:</small><br><img src="{{ $booking->user->sim_photo }}"
                                    style="height: 60px; border-radius: 4px; border: 1px solid #ccc; cursor: pointer;"
                                    onclick="window.open(this.src)"></div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- INFO MOBIL & HARGA -->
            <div>
                <h3 style="color: var(--primary); margin-bottom: 1rem;">🚗 Info Sewa & Keuangan</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 150px;">Mobil</td>
                        <td style="font-weight: bold;">: {{ $booking->car->brand }} {{ $booking->car->name }}
                            ({{ $booking->car->license_plate }})</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Durasi</td>
                        <td style="font-weight: bold;">: {{ date('d M Y', strtotime($booking->start_date)) }} s/d
                            {{ date('d M Y', strtotime($booking->end_date)) }} ({{ $booking->durasi }} Hari)
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Biaya Sewa Pokok</td>
                        <td style="font-weight: bold; color: #047857;">: Rp
                            {{ number_format($booking->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Uang Jaminan (Deposit)</td>
                        <td style="font-weight: bold; color: #D97706;">: Rp
                            {{ number_format($booking->deposit, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;">Total Tagihan Akhir</td>
                        <td style="font-weight: bold; font-size: 1.2rem; color: #E11D48;">: Rp
                            {{ number_format($booking->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>

                @if($booking->payment_proof)
                    <div style="margin-top: 1rem;">
                        <small>Bukti Transfer (Customer):</small><br>
                        <img src="{{ $booking->payment_proof }}"
                            style="height: 100px; border-radius: 4px; border: 1px solid #ccc; cursor: pointer;"
                            onclick="window.open(this.src)">
                    </div>
                @endif
            </div>
        </div>

        <!-- ACTION AREA (SMART BUTTONS) -->
        <div style="background: #F8FAFC; padding: 2rem; border-radius: 12px; border: 1px solid #E2E8F0;">
            <h3 style="margin-top: 0; color: #1e293b; margin-bottom: 1.5rem;">Tindakan Operasional</h3>

            @if($booking->status_pembayaran === 'Menunggu Verifikasi')
                <div
                    style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid var(--primary); margin-bottom: 1rem;">
                    <h4>✓ Verifikasi Pembayaran Customer</h4>
                    <p style="color: #64748B; margin-bottom: 1rem;">Customer telah mengunggah bukti pembayaran. Harap pastikan mutasi rekening valid sebelum menekan Lunas.</p>
                    @can('edit_bookings')
                    <form action="{{ route('admin.bookings.update-payment-status', $booking->id) }}" method="POST"
                        style="display: flex; gap: 1rem; align-items: center;">
                        @csrf
                        @method('PUT')

                        <div>
                            <label style="display: block; font-weight:bold; margin-bottom: 5px;">Input Nominal Deposit (Jaminan)</label>
                            <input type="number" name="deposit" value="{{ $booking->deposit > 0 ? $booking->deposit : '' }}"
                                placeholder="Rp 0 (Kosongkan bila tidak ada)"
                                style="padding: 0.6rem; border: 1px solid #ccc; width: 250px; border-radius: 4px;">
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
                <div
                    style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #F59E0B; margin-bottom: 1rem;">
                    <h4>🚗 Proses Serah Terima (Handover)</h4>
                    <p style="color: #64748B; margin-bottom: 1rem;">Lengkapi data awal kendaraan sebelum pelanggan membawa mobil keluar garasi.</p>
                    
                    @can('edit_bookings')
                    <form action="{{ route('admin.bookings.process-handover', $booking->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px;">KM Berangkat Odometer</label>
                                <input type="number" name="km_awal" required style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px;">Posisi BBM Awal (Misal: 3/4)</label>
                                <input type="text" name="bbm_awal" required style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-weight:bold; margin-bottom: 5px;">Cacat / Kondisi Awal Fisik Mobil</label>
                            <textarea name="kondisi_awal" required style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;" rows="2"></textarea>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-weight:bold; margin-bottom: 5px;">Foto Aktual Mobil Sebelum Diserahkan</label>
                            <input type="file" name="foto_awal" accept="image/*" required style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                        <button type="submit" style="background: #F59E0B; padding: 0.6rem 1.5rem; border: none; border-radius: 6px; font-weight:bold; color:white; cursor:pointer;">Mulai Masa Sewa -> Sedang Disewa</button>
                    </form>
                    @else
                        <p style="color: #EF4444; font-weight:bold;">Hubungi staf operasional untuk melakukan proses Serah Terima.</p>
                    @endcan
                </div>

            @elseif($booking->status_booking === 'Sedang Disewa')
                <div
                    style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #10B981; margin-bottom: 1rem;">
                    <h4>🔙 Proses Pengembalian (Return)</h4>
                    <p style="color: #64748B; margin-bottom: 1rem;">Lengkapi data akhir kedatangan mobil. Setelah ini, Anda dapat merinci Denda atau Kerusakan jika ada sebelum Finalisasi Invoice.</p>

                    @can('edit_bookings')
                    <form action="{{ route('admin.bookings.process-return', $booking->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px;">KM Akhir Odometer</label>
                                <input type="number" name="km_akhir" required style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px;">Posisi BBM Akhir</label>
                                <input type="text" name="bbm_akhir" required style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-weight:bold; margin-bottom: 5px;">Kondisi Awal Saat Kembali (Umum)</label>
                            <textarea name="kondisi_akhir" required style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;" rows="2">Mobil kembali dalam keadaan aman.</textarea>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-weight:bold; margin-bottom: 5px;">Foto Aktual Mobil Saat Dikembalikan</label>
                            <input type="file" name="foto_akhir" accept="image/*" required style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                        <button type="submit" style="background: #10B981; padding: 0.6rem 1.5rem; border: none; border-radius: 6px; font-weight:bold; color:white; cursor:pointer;">Mobil Diterima (Lanjut Pemeriksaan)</button>
                    </form>
                    @else
                        <p style="color: #EF4444; font-weight:bold;">Hubungi staf operasional untuk melakukan proses Pengembalian Kendaraan.</p>
                    @endcan
                </div>
            @elseif($booking->status_booking === 'Pemeriksaan')
                <div
                    style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #F59E0B; margin-bottom: 1rem;">
                    <h4>🔍 Pemeriksaan Rincian Denda & Kerusakan</h4>
                    <p style="color: #64748B; margin-bottom: 1rem;">Tambahkan log/rincian satu per satu jika ada kerusakan atau
                        denda terlambat. Transparansi sangat penting.</p>

                    <!-- ADD FINE FORM -->
                    @can('manage_fines')
                    <form action="{{ route('admin.fines.store', $booking->id) }}" method="POST" enctype="multipart/form-data"
                        style="background: #F8FAFC; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #E2E8F0;">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px; font-size: 0.85rem;">Jenis Biaya</label>
                                <select name="type" required style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
                                    <option value="Kerusakan">Kerusakan</option>
                                    <option value="Denda Terlambat">Denda Terlambat</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px; font-size: 0.85rem;">Bagian Mobil (Opsional)</label>
                                <input type="text" name="part_name" placeholder="Misal: Bumper Belakang" style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px; font-size: 0.85rem;">Tagihan (Rp)</label>
                                <input type="number" name="amount" required style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px; font-size: 0.85rem;">Deskripsi Detail Kerusakan</label>
                                <input type="text" name="description" required placeholder="Misal: Lecet cukup dalam akibat goresan" style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px; font-size: 0.85rem;">Upload Bukti Foto</label>
                                <input type="file" name="photo" accept="image/*" style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="padding: 0.4rem 1rem;">+ Tambah Log Biaya</button>
                    </form>
                    @endcan

                    <!-- FINES TABLE -->
                    @if($booking->fines->count() > 0)
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
                            <thead>
                                <tr style="background: #F1F5F9; text-align: left;">
                                    <th style="padding: 8px;">Daftar Biaya</th>
                                    <th style="padding: 8px;">Bagian / Deskripsi</th>
                                    <th style="padding: 8px; text-align: right;">Nominal (Rp)</th>
                                    <th style="padding: 8px;">Dicatat Oleh</th>
                                    <th style="padding: 8px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->fines as $fine)
                                    <tr style="border-bottom: 1px solid #E2E8F0;">
                                        <td style="padding: 8px;"><b>{{ $fine->type }}</b><br>
                                            @if($fine->photo_path)
                                                <a href="{{ Storage::url($fine->photo_path) }}" target="_blank"
                                                    style="font-size: 0.75rem;">Lihat Bukti Foto</a>
                                            @endif
                                        </td>
                                        <td style="padding: 8px;">
                                            {{ $fine->part_name ? $fine->part_name . ' - ' : '' }}{{ $fine->description }}</td>
                                        <td style="padding: 8px; text-align: right; color: #E11D48; font-weight: bold;">
                                            {{ number_format($fine->amount, 0, ',', '.') }}</td>
                                        <td style="padding: 8px;">{{ $fine->user->name ?? 'Admin' }}<br><small
                                                style="color:#64748B;">{{ $fine->created_at->format('d M H:i') }}</small></td>
                                        <td style="padding: 8px;">
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
                        <div
                            style="background: #F8FAFC; border: 1px dashed #CBD5E1; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <h5 style="margin: 0 0 10px 0; color: #64748B;">📜 Riwayat Audit Trail Kerusakan & Denda</h5>
                            <ul style="margin: 0; padding-left: 20px; font-size: 0.85rem; color: #475569;">
                                @foreach($booking->fineAuditLogs()->orderBy('created_at', 'desc')->get() as $log)
                                    <li style="margin-bottom: 5px;">
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
                        <button type="submit"
                            style="background: #1e40af; padding: 0.8rem 1.5rem; border: none; border-radius: 6px; font-weight:bold; color:white; cursor:pointer; width: 100%;">🏁
                            SIMPAN SEMUA & FINALISASI INVOICE</button>
                    </form>
                    @else
                    <p style="color: #F59E0B; text-align: center; border: 1px dashed #F59E0B; padding: 1rem; border-radius: 6px;">Menunggu kasir utama untuk Finalisasi Invoice & Denda.</p>
                    @endcan
                </div>
            @elseif($booking->status_booking === 'Menunggu Pelunasan')
                <div
                    style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #EF4444; margin-bottom: 1rem;">
                    <h4>⚠️ Menunggu Pelunasan Tagihan Susulan</h4>
                    <p style="color: #64748B; margin-bottom: 1rem;">Pelanggan memiliki Tagihan Susulan sebesar <b>Rp
                            {{ number_format($booking->tagihan_susulan, 0, ',', '.') }}</b> yang melebihi batas Deposit.
                        Transaksi tidak dapat diselesaikan ke tahap Selesai sebelum tunggakan dilunasi.</p>
                    @can('edit_bookings')
                    <form action="{{ route('admin.bookings.update-payment-status', $booking->id) }}" method="POST"
                        style="margin-top: 1rem;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status_pembayaran" value="Lunas">
                        <input type="hidden" name="deposit" value="{{ $booking->deposit }}">
                        <button type="submit"
                            style="background: #10B981; padding: 0.6rem 1.5rem; border: none; border-radius: 6px; font-weight:bold; color:white; cursor:pointer;">Konfirmasi
                            Pelunasan & Tutup Booking Transaksi (Lunas)</button>
                    </form>
                    @endcan
                </div>
            @elseif($booking->status_booking === 'Selesai')
                <div style="text-align: center; padding: 2rem;">
                    <h3>✅ Transaksi Selesai</h3>
                    <p style="color: #64748b;">Mobil telah dikembalikan dan Invoice telah resmi diterbitkan.</p>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary" style="margin-top: 1rem;">Kembali ke
                        Riwayat</a>
                    <a href="{{ route('bookings.invoice', $booking->id) }}" class="btn btn-outline" target="_blank"
                        style="margin-top: 1rem; margin-left:1rem; border: 1px solid #1e40af; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; color: #1e40af;">Cetak
                        Invoice Final</a>
                </div>
            @else
                <div style="text-align: center; padding: 2rem;">
                    <p style="color: #64748b;">Menunggu aksi dari Pelanggan. (Status: {{ $booking->status_booking }})</p>
                </div>
            @endif
        </div>

    </div>
@endsection