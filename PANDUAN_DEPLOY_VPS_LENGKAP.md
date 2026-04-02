# Panduan Lengkap Deploy Laravel ke VPS (Ubuntu 24.04)
## Proyek: RentalMobil

Dokumen ini adalah panduan langkah-demi-langkah "dari nol" untuk mempublikasikan aplikasi RentalMobil ke server VPS Anda.

---

### Tahap 1: Persiapan Server (SSH Login)

1.  **Update Server:**
    ```bash
    sudo apt update && sudo apt upgrade -y
    ```
2.  **Install Web Server & Database:**
    ```bash
    sudo apt install nginx mysql-server -y
    ```
3.  **Install PHP 8.3 & Ekstensi (Sesuaikan versi):**
    ```bash
    sudo apt install php-fpm php-mysql php-xml php-mbstring php-curl php-zip php-bcmath php-intl -y
    ```
4.  **Install Composer:**
    ```bash
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    ```
5.  **Install Node.js & NPM (untuk Vite):**
    ```bash
    curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
    sudo apt install -y nodejs
    ```

---

### Tahap 2: Konfigurasi Database

1.  **Masuk ke MySQL:**
    ```bash
    sudo mysql
    ```
2.  **Buat Database & User:**
    ```sql
    CREATE DATABASE rental_mobil;
    CREATE USER 'admin_rental'@'localhost' IDENTIFIED BY 'PasswordRahasiaAnda';
    GRANT ALL PRIVILEGES ON rental_mobil.* TO 'admin_rental'@'localhost';
    FLUSH PRIVILEGES;
    EXIT;
    ```

---

### Tahap 3: Deployment Aplikasi

1.  **Masuk ke folder Web:**
    ```bash
    cd /var/www
    ```
2.  **Clone Project dari Git:**
    ```bash
    git clone https://github.com/username/RentalMobil.git
    cd RentalMobil
    ```
3.  **Install Dependencies:**
    ```bash
    composer install --no-dev --optimize-autoloader
    npm install 
    npm run build
    ```
4.  **Konfigurasi Environment:**
    ```bash
    cp .env.example .env
    nano .env
    ```
    *Sesuaikan:*
    - `APP_ENV=production`
    - `APP_DEBUG=false`
    - `APP_URL=http://domain-anda.com`
    - `DB_DATABASE=rental_mobil`
    - `DB_USERNAME=admin_rental`
    - `DB_PASSWORD=PasswordRahasiaAnda`
5.  **Generate Key & Migrate:**
    ```bash
    php artisan key:generate
    php artisan migrate --force
    php artisan storage:link
    ```

---

### Tahap 4: Set Permission (PENTING!)

Agar Laravel bisa berjalan, server butuh akses tulis ke folder tertentu:
```bash
sudo chown -R www-data:www-data /var/www/RentalMobil
sudo chmod -R 775 /var/www/RentalMobil/storage
sudo chmod -R 775 /var/www/RentalMobil/bootstrap/cache
```

---

### Tahap 5: Konfigurasi Nginx

1.  **Buat file konfigurasi:**
    ```bash
    sudo nano /etc/nginx/sites-available/rentalmobil
    ```
2.  **Isi dengan script berikut:**
    ```nginx
    server {
        listen 80;
        server_name domain-anda.com;
        root /var/www/RentalMobil/public;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";

        index index.php;

        charset utf-8;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
    ```
3.  **Aktifkan Konfigurasi:**
    ```bash
    sudo ln -s /etc/nginx/sites-available/rentalmobil /etc/nginx/sites-enabled/
    sudo nginx -t
    sudo systemctl restart nginx
    ```

---

### Tahap 6: SSL (HTTPS Gratis)

Gunakan Certbot untuk mengaktifkan HTTPS:
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d domain-anda.com
```

---

### Tahap 7: Otomasi (Maintenance)

1.  **Optimasi Laravel:**
    Setiap ada update code, jalankan perintah ini di server:
    ```bash
    php artisan optimize
    ```
2.  **Cron Job (Task Scheduler):**
    ```bash
    crontab -e
    ```
    Tambahkan di baris paling bawah:
    `* * * * * cd /var/www/RentalMobil && php artisan schedule:run >> /dev/null 2>&1`

---

## Catatan Tambahan (Deployment Notes)
- **Email:** Pastikan untuk mengatur `MAIL_MAILER` di `.env` agar notifikasi email berjalan di server.
- **Queue:** Jika menggunakan fitur notifikasi massal, instal **Supervisor** untuk menjalankan `php artisan queue:work`.
- **Hati-hati:** Jangan pernah hapus folder `.git` di server agar mudah jika ingin melakukan `git pull` update terbaru.
