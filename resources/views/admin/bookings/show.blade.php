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
                    <p style="color: #64748B; margin-bottom: 1rem;">Customer telah mengunggah bukti pembayaran. Harap pastikan
                        mutasi rekening valid sebelum menekan Lunas.</p>
                    <form action="{{ route('admin.bookings.update-payment-status', $booking->id) }}" method="POST"
                        style="display: flex; gap: 1rem; align-items: center;">
                        @csrf
                        @method('PUT')

                        <div>
                            <label style="display: block; font-weight:bold; margin-bottom: 5px;">Input Nominal Deposit
                                (Jaminan)</label>
                            <input type="number" name="deposit" value="{{ $booking->deposit > 0 ? $booking->deposit : '' }}"
                                placeholder="Rp 0 (Kosongkan bila tidak ada)"
                                style="padding: 0.6rem; border: 1px solid #ccc; width: 250px; border-radius: 4px;">
                        </div>

                        <input type="hidden" name="status_pembayaran" value="Lunas">
                        <!-- Deposit not yet handled completely by updatePaymentStatus controller directly, but UI is ready. For now we will update Controller shortly. -->
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
                </div>

            @elseif($booking->status_booking === 'Booking Dikonfirmasi')
                <div
                    style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #F59E0B; margin-bottom: 1rem;">
                    <h4>🚗 Proses Serah Terima (Handover)</h4>
                    <p style="color: #64748B; margin-bottom: 1rem;">Lengkapi data awal kendaraan sebelum pelanggan membawa mobil
                        keluar garasi.</p>

                    <form action="{{ route('admin.bookings.process-handover', $booking->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px;">KM Berangkat
                                    Odometer</label>
                                <input type="number" name="km_awal" required
                                    style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px;">Posisi BBM Awal (Misal:
                                    3/4)</label>
                                <input type="text" name="bbm_awal" required
                                    style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-weight:bold; margin-bottom: 5px;">Cacat / Kondisi Awal Fisik
                                Mobil</label>
                            <textarea name="kondisi_awal" required
                                style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;"
                                rows="2"></textarea>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-weight:bold; margin-bottom: 5px;">Foto Aktual Mobil Sebelum
                                Diserahkan</label>
                            <input type="file" name="foto_awal" accept="image/*" required
                                style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                        <button type="submit"
                            style="background: #F59E0B; padding: 0.6rem 1.5rem; border: none; border-radius: 6px; font-weight:bold; color:white; cursor:pointer;">Mulai
                            Masa Sewa -> Sedang Disewa</button>
                    </form>
                </div>

            @elseif($booking->status_booking === 'Sedang Disewa')
                <div
                    style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #10B981; margin-bottom: 1rem;">
                    <h4>🔙 Proses Pengembalian (Return & Kalkulasi Akhir)</h4>
                    <p style="color: #64748B; margin-bottom: 1rem;">Lengkapi data akhir kedatangan mobil. Tagihan
                        denda/kerusakan otomatis dipotong dari Saldo Deposit Rp
                        {{ number_format($booking->deposit, 0, ',', '.') }}.
                    </p>

                    <form action="{{ route('admin.bookings.process-return', $booking->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px;">KM Akhir Odometer</label>
                                <input type="number" name="km_akhir" required
                                    style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px;">Posisi BBM Akhir</label>
                                <input type="text" name="bbm_akhir" required
                                    style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px; color:#E11D48;">Nominal
                                    Denda Terlambat (Rp)</label>
                                <input type="number" name="denda_terlambat" value="0"
                                    style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div>
                                <label style="display: block; font-weight:bold; margin-bottom: 5px; color:#E11D48;">Biaya
                                    Kerusakan Baru (Rp)</label>
                                <input type="number" name="biaya_kerusakan" value="0"
                                    style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-weight:bold; margin-bottom: 5px;">Catatan Kerusakan Akhir</label>
                            <textarea name="kondisi_akhir" required
                                style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;"
                                rows="2">Mobil kembali dalam keadaan aman.</textarea>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-weight:bold; margin-bottom: 5px;">Foto Aktual Mobil Saat
                                Dikembalikan</label>
                            <input type="file" name="foto_akhir" accept="image/*" required
                                style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                        <button type="submit"
                            style="background: #10B981; padding: 0.6rem 1.5rem; border: none; border-radius: 6px; font-weight:bold; color:white; cursor:pointer;">Mobil
                            Diterima & Finalisasi Invoice</button>
                    </form>
                </div>
            @elseif($booking->status_booking === 'Menunggu Pelunasan')
                <div
                    style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #EF4444; margin-bottom: 1rem;">
                    <h4>⚠️ Menunggu Pelunasan Tagihan Susulan</h4>
                    <p style="color: #64748B; margin-bottom: 1rem;">Pelanggan memiliki Tagihan Susulan sebesar <b>Rp
                            {{ number_format($booking->tagihan_susulan, 0, ',', '.') }}</b> yang melebihi batas Deposit.
                        Transaksi tidak dapat diselesaikan ke tahap Selesai sebelum tunggakan dilunasi.</p>
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