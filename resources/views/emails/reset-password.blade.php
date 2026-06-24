<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; padding: 20px; }
        .container { background-color: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        .header { color: #4F46E5; font-size: 24px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        .content { font-size: 16px; color: #374151; line-height: 1.6; }
        .footer { font-size: 14px; color: #6B7280; text-align: center; margin-top: 30px; }
        .btn { background-color: #4F46E5; color: white !important; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Halo {{ $user->name }}</div>
        <div class="content">
            Kami menerima permintaan untuk mengatur ulang kata sandi akun RentalMobil Anda. Silakan klik tombol di bawah ini untuk melanjutkan:
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $url }}" class="btn">Atur Ulang Password</a>
            </div>
            Jika Anda tidak merasa meminta pengaturan ulang kata sandi,abaikan email ini. Tautan ini akan kedaluwarsa dalam 60 menit.
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} RentalMobil.
        </div>
    </div>
</body>
</html>
