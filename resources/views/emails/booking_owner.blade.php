<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; padding: 20px; color: #1e293b; }
        .container { background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); max-width: 600px; margin: auto; border-top: 5px solid #4f46e5; }
        .header { font-size: 24px; font-weight: 800; color: #4f46e5; margin-bottom: 24px; text-align: center; }
        .info-card { background-color: #f1f5f9; padding: 20px; border-radius: 12px; margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; }
        .info-label { font-weight: 600; color: #64748b; }
        .info-value { font-weight: 700; color: #1e293b; text-align: right; }
        .footer { text-align: center; font-size: 14px; color: #94a3b8; margin-top: 30px; }
        .btn { display: block; text-align: center; background-color: #4f46e5; color: white; padding: 14px; border-radius: 10px; text-decoration: none; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Pesanan Sewa Baru!</div>
        <p>Halo Admin,</p>
        <p>Ada pesanan baru yang masuk ke sistem RentalMobil. Berikut adalah detail pesanan tersebut:</p>

        <div class="info-card">
            <div style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #4f46e5;">💻 Data Penyewa</div>
            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value">{{ $user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">No. Telepon</span>
                <span class="info-value">{{ $user->phone ?? '-' }}</span>
            </div>
        </div>

        <div class="info-card">
            <div style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #4f46e5;">🚗 Detail Armada</div>
            <div class="info-row">
                <span class="info-label">Unit Mobil</span>
                <span class="info-value">{{ $car->brand }} {{ $car->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tgl Sewa</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Tagihan</span>
                <span class="info-value" style="color: #ef4444;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        <a href="{{ config('app.url') }}/admin/bookings" class="btn">Kelola di Dashboard Admin</a>

        <div class="footer">
            &copy; {{ date('Y') }} RentalMobil Management System.
        </div>
    </div>
</body>
</html>
