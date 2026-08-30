<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $booking->nomor_booking }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin/invoice.css') }}">
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <div>
                <div class="title">INVOICE RENTAL</div>
                <div class="title-meta">No: #{{ $booking->nomor_booking }}</div>
                <div class="meta-text">Tanggal: {{ $booking->created_at->format('d M Y') }}</div>
            </div>
            <div class="header-right">
                <div class="company-name">RentalMobil Premium</div>
                <div class="meta-text">Jl. Sudirman No 123, Jakarta</div>
                <div class="meta-text">WA: 085748174062</div>
            </div>
        </div>

        <div class="customer-info-box">
            <div>
                <h4 class="info-title">Ditujukan Kepada:</h4>
                <div class="customer-name">{{ $booking->user->name }}</div>
                <div class="meta-text">{{ $booking->user->phone ?? '-' }}</div>
                <div class="meta-text">{{ $booking->user->address ?? 'Alamat belum diatur' }}</div>
            </div>
            <div class="status-box">
                <h4 class="info-title">Status Sewa:</h4>
                <div class="status">{{ strtoupper($booking->status_booking) }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Deskripsi Layanan</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Durasi/Jumlah</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sewa Mobil: {{ $booking->car->brand }} {{ $booking->car->name }}<br><small
                            class="meta-text">{{ date('d M Y', strtotime($booking->start_date)) }} s/d
                            {{ date('d M Y', strtotime($booking->end_date)) }}</small></td>
                    <td class="text-right">Rp {{ number_format($booking->harga_per_hari, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $booking->durasi }} Hari</td>
                    <td class="text-right">Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</td>
                </tr>
                @if($booking->denda_terlambat > 0)
                    <tr>
                        <td>Denda Keterlambatan Pengembalian</td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                        <td class="text-right">Rp {{ number_format($booking->denda_terlambat, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if($booking->biaya_kerusakan > 0)
                    <tr>
                        <td>Biaya Perbaikan/Kerusakan Kerusakan</td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                        <td class="text-right">Rp {{ number_format($booking->biaya_kerusakan, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" class="total-label"><strong>TOTAL BIAYA:</strong>
                    </td>
                    <td class="total total-val">Rp
                        {{ number_format($booking->total, 0, ',', '.') }}
                    </td>
                </tr>
                <tr class="row-spacing">
                    <td colspan="3" class="label-muted">Pembayaran Awal /
                        Booking:</td>
                    <td class="val-subtotal">Rp
                        {{ number_format($booking->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr class="row-sm-spacing">
                    <td colspan="3" class="label-muted">Jaminan Deposit
                        Diserahkan:</td>
                    <td class="val-deposit">Rp
                        {{ number_format($booking->deposit, 0, ',', '.') }}</td>
                </tr>
                @php
                    $dendaKerusakan = $booking->denda_terlambat + $booking->biaya_kerusakan;
                @endphp
                @if($dendaKerusakan > 0 && $booking->deposit >= $dendaKerusakan)
                    <tr class="row-sm-spacing">
                        <td colspan="3" class="label-muted">Deposit Digunakan Utk
                            Denda/Rusak:</td>
                        <td class="val-deduction">- Rp
                            {{ number_format($dendaKerusakan, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="row-spacing">
                        <td colspan="3" class="label-success">SISA
                            DEPOSIT DIKEMBALIKAN TUNAI:</td>
                        <td class="val-success">
                            Rp {{ number_format($booking->deposit - $dendaKerusakan, 0, ',', '.') }}</td>
                    </tr>
                @elseif($dendaKerusakan > 0 && $booking->deposit < $dendaKerusakan)
                    <tr class="row-sm-spacing">
                        <td colspan="3" class="label-muted">Deposit Disita Penuh
                            Utk Denda:</td>
                        <td class="val-deduction">- Rp
                            {{ number_format($booking->deposit, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="row-spacing">
                        <td colspan="3" class="label-danger">
                            KEKURANGAN / TAGIHAN SUSULAN:</td>
                        <td class="val-danger">
                            Rp {{ number_format($booking->tagihan_susulan, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="final-status-row">
                    <td colspan="3" class="final-status-label">STATUS AKHIR:</td>
                    <td class="final-status-val {{ $booking->status_pembayaran === 'Lunas' ? 'status-lunas' : 'status-belum' }}">
                        {{ strtoupper($booking->status_pembayaran) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer-notes">
            <p>Terima kasih telah menyewa kendaraan di RentalMobil Premium!</p>
            <p>Invoice ini sah sebagai bukti transaksi yang diterbitkan secara otomatis.</p>
        </div>

        <div class="print-btn">
            <button onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
            <button onclick="history.back()" class="btn-back">Kembali</button>
        </div>
    </div>
</body>

</html>