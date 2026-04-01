 # Panduan Deployment Laravel (RentalMobil)

 Dokumen ini berisi langkah-langkah untuk mendeploy aplikasi Laravel **RentalMobil** ke lingkungan produksi (Production).

 ---

 ## 1. Persiapan Awal (Local)

 Sebelum mengunggah file, pastikan aplikasi siap untuk lingkungan produksi:

 1.  **Build Assets (Vite):**
    Pastikan semua file CSS/JS sudah di-*compile*.
    ```bash
    npm run build
    ```
 2.  **Cek Dependencies:**
    Pastikan `composer.json` sudah rapi.
    ```bash
    composer install --optimize-autoloader --no-dev
    ```

 ---

 ## 2. Pilihan Metode Deployment

 ### Metode A: VPS (Recommended - Ubuntu/Nginx)

 Ini adalah cara yang paling direkomendasikan untuk performa dan keamanan terbaik.

 #### Langkah-langkah:
 1.  **Clone Repository:**
    ```bash
    git clone https://github.com/username/RentalMobil.git /var/www/rentalmobil
    ```
 2.  **Konfigurasi `.env`:**
    Salin `.env.example` menjadi `.env` dan sesuaikan:
    -   `APP_ENV=production`
    -   `APP_DEBUG=false`
    -   `APP_URL=https://domainanda.com`
    -   Konfigurasi Database (MySQL)
    -   Konfigurasi WhatsApp (Fonnte)
 3.  **Permissions:**
    Laravel membutuhkan akses tulis ke folder `storage` dan `bootstrap/cache`.
    ```bash
    chown -R www-data:www-data /var/www/rentalmobil
    chmod -R 775 /var/www/rentalmobil/storage
    chmod -R 775 /var/www/rentalmobil/bootstrap/cache
    ```
 4.  **Optimasi Laravel:**
    Jalankan perintah ini di server:
    ```bash
    php artisan migrate --force
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan storage:link
    ```
 5.  **Konfigurasi Nginx:**
    Pastikan `root` mengarah ke folder `/public`.

 ---

 ### Metode B: Shared Hosting (Cpanel)

 Jika Anda menggunakan Shared Hosting, biasanya Anda tidak memiliki akses SSH penuh.

 #### Langkah-langkah:
 1.  **Kompres File:**
    Zip semua folder project **KECUALI** folder `node_modules`.
 2.  **Upload & Extract:**
    Upload ke File Manager. Disarankan meletakkan file Laravel di luar folder `public_html` untuk keamanan.
    -   Struktur contoh:
        -   `/home/user/laravel_app` (Semua file Laravel)
        -   `/home/user/public_html` (Isi dari folder `/public` Laravel)
 3.  **Edit `index.php`:**
    Jika folder `/public` dipindah ke `public_html`, sesuaikan path di `index.php`:
    ```php
    require __DIR__.'/../laravel_app/vendor/autoload.php';
    $app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';
    ```
 4.  **Storage Link di Shared Hosting:**
    Karena tidak ada SSH, buatlah route sementara di `routes/web.php`:
    ```php
    Route::get('/init-link', function () {
        Artisan::call('storage:link');
        return "Storage Link Created";
    });
    ```
    Akses URL tersebut sekali, lalu hapus routenya.
 
 ---

 ## 3. Checklist Penting Setelah Deploy

 -   [ ] **Generate App Key:** `php artisan key:generate` (Jika belum ada di .env).
 -   [ ] **Migrate Database:** Pastikan database produksi sudah dibuat dan di-migrate.
 -   [ ] **HTTPS:** Pastikan SSL (Certbot/Cloudflare) sudah aktif.
 -   [ ] **Task Scheduling (Cron):**
    Tambahkan entri ini ke Crontab server:
    ```bash
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
    ```
 -   [ ] **Queue Worker (Jika ada API/Email/WA masal):**
    Gunakan **Supervisor** untuk menjalankan `php artisan queue:work`.

 ---

 ## 4. Tips Perawatan
 -   Kapanpun Anda melakukan perubahan code, jangan lupa jalankan `php artisan optimize` di server.
 -   Selalu backup database secara berkala.
 -   Jangan pernah mengaktifkan `APP_DEBUG=true` di server produksi karena berbahaya (bisa membocorkan kredensial database).
