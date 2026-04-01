# Persyaratan Menggunakan Nomor WhatsApp di Fonnte

Untuk menghubungkan aplikasi Anda agar bisa mengirim pesan notifikasi otomatis (menggunakan nomor WhatsApp Anda sendiri sebagai *Bot/Pengirim*), ini adalah daftar langkah (*checklist*) yang wajib Anda siapkan dan lakukan:

## 📱 Persiapan Perangkat & Nomor
- [ ] **Nomor Aktif**: Siapkan satu nomor bebas yang bisa menerima SMS/OTP. Disarankan menggunakan **WhatsApp Business** agar terlihat lebih profesional, tetapi WhatsApp biasa juga bisa digunakan.
- [ ] **Aplikasi Login**: Nomor tersebut harus sedang didaftarkan/login di dalam satu aplikasi WhatsApp di ponsel (*smartphone*).
- [ ] **Kecepatan Internet**: Pastikan HP tersebut memiliki koneksi internet yang stabil untuk kelancaran API (walaupun fitur terbaru WhatsApp *Multi-Device* memungkinkan Fonnte tetap jalan sekalipun HP mati sementara).

## 💻 Pendaftaran & Tautan ke Server Fonnte
- [ ] **Daftar Akun Fonnte**: Kunjungi [Web Fonnte (fonnte.com)](https://fonnte.com) dan buat akun baru. Fonnte biasanya memberikan *free trial* *(kuota pesan harian)* untuk akun baru yang cukup untuk pengetesan.
- [ ] **Tambah Perangkat (Add Device)**: Di dalam dashboard web Fonnte, navigasikan ke menu **Device** > lalu klik tombol **Add Device**.
- [ ] **Scan QR Code**: Sistem web Fonnte akan memunculkan *QR Code*. Buka aplikasi WhatsApp di HP Anda > pilih menu titik tiga > masuk ke **Perangkat Taut (Linked Devices)** > Tekan tombol **Tautkan Perangkat (Link a Device)**. Setelah itu, scan QR Code yang muncul di layar laptop/komputer Anda.
- [ ] **Tunggu Status Active**: Pastikan status *Device* di Dashboard Fonnte Anda kini berubah dari merah (*Disconnected*) menjadi hijau (*Connected* atau *Active*).

## 🔑 Integrasi Token ke Aplikasi Rental Anda
- [ ] **Ambil Token**: Setelah HP berhasil dipasangkan (sinkronisasi selesai), silakan _copy_ **API Token** (*kombinasi huruf & kode unik*) yang tertera pada baris device Anda di Dashboard Fonnte.
- [ ] **Simpan ke Codebase**: Buka proyek RentalMobil Anda, cari file bernama `.env`, lalu tempelkan Token tersebut di baris ini:
  ```env
  FONNTE_TOKEN=TempelTokenAndaDisini_TanpaSpasi
  ```
- [ ] **Konfigurasi Penerima (Admin)**: Tentukan satu nomor tujuan utama (biasanya nomor owner / operator yang berjaga) yang akan menerima semua ping notifikasi dari website ketika pelanggan memesan mobil, lalu isikan di `.env` (isi menggunakan 08x atau 628x):
  ```env
  ADMIN_WA_NUMBER=0812xxxxxxxxx
  ```

> [!TIP]
> **Praktik Terbaik Praktikal:** Gunakan 2 nomor WhatsApp jika memungkinkan!
> * **Nomor A (untuk didaftarkan ke Fonnte):** Berfungsi layaknya *"mesin robot"* kasir yang mengirim bon otomatis via API.
> * **Nomor B (Nomor Anda Pribadi):** Didaftarkan sebagai `ADMIN_WA_NUMBER` untuk Anda menerima rangkuman / konfirmasi data pemesanan.
> 
> *Namun jika Anda hanya memiliki 1 nomor HP, Fonnte tetap mengizinkan nomor Anda mengirimkan API Tembakan Pesan ke nomor Anda sendiri!*
