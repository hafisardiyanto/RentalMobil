<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $booking->nomor_booking }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            padding: 40px;
            color: #333;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            color: #1e40af;
        }

        .info span {
            display: block;
        }

        table {
            width: 100%;
            text-align: left;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #E2E8F0;
        }

        th {
            background-color: #F8FAFC;
        }

        .total {
            font-weight: bold;
            font-size: 20px;
            color: #047857;
            text-align: right;
        }

        .status {
            padding: 5px 12px;
            border-radius: 4px;
            background: #D1FAE5;
            color: #065F46;
            font-weight: bold;
            display: inline-block;
        }

        .print-btn {
            text-align: center;
            margin-top: 30px;
        }

        .print-btn button {
            padding: 10px 20px;
            background: #1e40af;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        @media print {
            .print-btn {
                display: none;
            }

            .invoice-box {
                box-shadow: none;
                border: none;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <div>
                <div class="title">INVOICE RENTAL</div>
                <div style="margin-top: 10px; color: #64748B;">No: #{{ $booking->nomor_booking }}</div>
                <div style="color: #64748B;">Tanggal: {{ $booking->created_at->format('d M Y') }}</div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: bold; font-size: 18px;">RentalMobil Premium</div>
                <div style="color: #64748B;">Jl. Sudirman No 123, Jakarta</div>
                <div style="color: #64748B;">WA: 085748174062</div>
            </div>
        </div>

        <div style="margin-bottom: 30px; display: flex; justify-content: space-between;">
            <div>
                <h4 style="margin-bottom: 5px;">Ditujukan Kepada:</h4>
                <div style="font-weight: bold;">{{ $booking->user->name }}</div>
                <div style="color: #64748B;">{{ $booking->user->phone ?? '-' }}</div>
                <div style="color: #64748B;">{{ $booking->user->address ?? 'Alamat belum diatur' }}</div>
            </div>
            <div style="text-align: right;">
                <h4 style="margin-bottom: 5px;">Status Sewa:</h4>
                <div class="status">{{ strtoupper($booking->status_booking) }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Deskripsi Layanan</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: right;">Durasi/Jumlah</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sewa Mobil: {{ $booking->car->brand }} {{ $booking->car->name }}<br><small
                            style="color: #64748B;">{{ date('d M Y', strtotime($booking->start_date)) }} s/d
                            {{ date('d M Y', strtotime($booking->end_date)) }}</small></td>
                    <td style="text-align: right;">Rp {{ number_format($booking->harga_per_hari, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ $booking->durasi }} Hari</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</td>
                </tr>
                @if($booking->denda_terlambat > 0)
                    <tr>
                        <td>Denda Keterlambatan Pengembalian</td>
                        <td style="text-align: right;">-</td>
                        <td style="text-align: right;">-</td>
                        <td style="text-align: right;">Rp {{ number_format($booking->denda_terlambat, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if($booking->biaya_kerusakan > 0)
                    <tr>
                        <td>Biaya Perbaikan/Kerusakan Kerusakan</td>
                        <td style="text-align: right;">-</td>
                        <td style="text-align: right;">-</td>
                        <td style="text-align: right;">Rp {{ number_format($booking->biaya_kerusakan, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; padding-top: 20px;"><strong>TOTAL KESELURUHAN:</strong>
                    </td>
                    <td class="total" style="padding-top: 20px;">Rp {{ number_format($booking->total, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 50px; text-align: center; color: #64748B;">
            <p>Terima kasih telah menyewa kendaraan di RentalMobil Premium!</p>
            <p>Invoice ini sah sebagai bukti transaksi yang diterbitkan secara otomatis.</p>
        </div>

        <div class="print-btn">
            <button onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
            <button onclick="history.back()" style="background: #64748B; margin-left: 10px;">Kembali</button>
        </div>
    </div>
</body>

</html>