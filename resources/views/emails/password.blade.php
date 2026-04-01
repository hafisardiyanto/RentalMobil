<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; padding: 20px; }
        .container { background-color: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        .header { color: #4F46E5; font-size: 24px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        .content { font-size: 16px; color: #374151; line-height: 1.6; }
        .password-box { background-color: #F3F4F6; padding: 15px; border-radius: 8px; text-align: center; font-size: 20px; font-weight: bold; color: #111827; margin: 20px 0; border: 1px dashed #4F46E5; }
        .footer { font-size: 14px; color: #6B7280; text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Selamat Datang di RentalMobil!</div>
        <div class="content">
            Halo <strong>{{ $user->name }}</strong>,<br><br>
            Selamat bergabung dengan RentalMobil! Akun Anda telah berhasil dibuat. Berikut adalah ringkasan akun Anda untuk login secara manual:
            <div class="password-box">{{ $password }}</div>
            Simpan informasi ini dengan aman. Anda dapat mengubah password Anda kapan saja melalui halaman profil.
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} RentalMobil. Perjalanan Mewah Mulai dari Sini.
        </div>
    </div>
</body>
</html>
