# Implementasi Notifikasi WhatsApp (Auth)

- [x] Tambahkan kolom `phone` ke tabel `users` (Sudah dilakukan sebelumnya).
- [ ] Buat Trait `WhatsappHelper` untuk sanitasi nomor telepon agar bisa dipakai di banyak controller.
- [ ] Update `AuthController@processRegister` untuk mengirim WhatsApp "Registrasi Berhasil" ke User via Fonnte.
- [ ] Implementasi Fitur Lupa Password:
    - [ ] Tambah Route `/forgot-password`.
    - [ ] Tambah View `auth.forgot-password`.
    - [ ] Update `AuthController` untuk mengirim **Reset Link** atau **Password Baru** via WhatsApp Fonnte.
- [ ] Tambahkan link "Lupa Password" di halaman Login.
- [ ] Verifikasi pengiriman pesan ke nomor User.
