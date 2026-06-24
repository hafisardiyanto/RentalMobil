#!/bin/bash
# ============================================================
# deploy.sh - Script otomatis deploy Laravel ke VPS
# Jalankan di VPS setelah clone repository
# ============================================================

set -e  # Stop jika ada error

echo "🚀 Memulai deployment RentalMobil..."

# ─────────────────────────────────────────
# 1. Cek apakah .env sudah ada
# ─────────────────────────────────────────
if [ ! -f .env ]; then
    echo "📋 Menyalin .env.production ke .env..."
    cp .env.production .env
    echo "⚠️  PENTING: Edit .env dan sesuaikan konfigurasi!"
    echo "    nano .env"
    exit 1
fi

# ─────────────────────────────────────────
# 2. Build & jalankan containers
# ─────────────────────────────────────────
echo "🐳 Membangun Docker containers..."
docker compose down --remove-orphans
docker compose build --no-cache
docker compose up -d

# ─────────────────────────────────────────
# 3. Tunggu database siap
# ─────────────────────────────────────────
echo "⏳ Menunggu database siap..."
sleep 15

# ─────────────────────────────────────────
# 4. Generate APP_KEY jika belum ada
# ─────────────────────────────────────────
APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ]; then
    echo "🔑 Membuat APP_KEY..."
    docker compose exec app php artisan key:generate
fi

# ─────────────────────────────────────────
# 5. Jalankan perintah Laravel
# ─────────────────────────────────────────
echo "⚙️  Menjalankan perintah Laravel..."
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
docker compose exec app php artisan storage:link

# ─────────────────────────────────────────
# 6. Set permissions
# ─────────────────────────────────────────
echo "🔒 Mengatur permissions..."
docker compose exec app chown -R www-data:www-data /var/www/html/storage
docker compose exec app chmod -R 775 /var/www/html/storage
docker compose exec app chmod -R 775 /var/www/html/bootstrap/cache

echo ""
echo "✅ Deployment selesai!"
echo "🌐 Akses aplikasi di: http://$(curl -s ifconfig.me)"
echo "📊 phpMyAdmin di:     http://$(curl -s ifconfig.me):8080"
echo ""
