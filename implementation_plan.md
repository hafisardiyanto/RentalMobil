# Rencana Implementasi: Notifikasi Email Registrasi

Kita akan menambahkan fitur pengiriman email otomatis ke alamat email yang dimasukkan user saat mereka mendaftar di website.

## ⚠️ Prasyarat Penting
Agar email ini benar-benar terkirim, Anda **wajib** mengisi `MAIL_PASSWORD` di file `.env` dengan **App Password** dari Google Anda. Jika kosong, sistem akan memberikan error saat registrasi karena tidak bisa terhubung ke Mail Server.

---

## 🛠️ Langkah-Langkah Teknis

### 1. Penyesuaian Template Email
#### [MODIFY] `resources/views/emails/password.blade.php`
- Mengubah teks agar lebih umum (saat ini teksnya bertuliskan "bergabung menggunakan Akun Google"). Kita akan mengubahnya menjadi "Selamat bergabung di RentalMobil".

### 2. Logika Pengiriman di Backend
#### [MODIFY] `app/Http/Controllers/AuthController.php`
- Menambahkan baris kode `Mail::to($user->email)->send(new SendPasswordMail($user, $request->password))` di dalam method `processRegister`.
- Menambahkan `use Illuminate\Support\Facades\Mail;` jika belum ada.

### 3. Pengetesan
- Mencoba mendaftar akun baru dan memastikan tidak ada error (asalkan SMTP sudah dikonfigurasi dengan benar di `.env`).

---

## 📝 Task List
- [ ] Update template email agar teksnya fleksibel untuk registrasi biasa.
- [ ] Tambahkan trigger pengiriman email di `AuthController@processRegister`.
- [ ] Verifikasi kode.

---

## 📝 Open Questions
- Apakah isi emailnya ingin ditambahkan informasi lain? (Misal: link ke halaman bantuan atau syarat ketentuan rental).
